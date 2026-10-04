# Pure and Preloved — Developer Documentation

> Living document. Update it in the same commit as any change to architecture,
> routes, permissions, caching, configuration, or deployment.

---

## 1. Overview

Pure and Preloved is a jewelry e-commerce site with a custom admin panel, built on
**Laravel 10** (PHP 8.1+, developed on PHP 8.3).

| Area | Highlights |
|---|---|
| Storefront | Home page builder sections, shop with filters, product pages, quick view, cart, wishlist, checkout (guest + logged-in), CMS pages, blog, contact form |
| Customer account | Registration + email verification, profile, addresses, password change, order history |
| Admin panel (`/admin`) | Products, categories, featured products, orders, shipping methods, customers, staff, roles, CMS pages, homepage, footer, social links, blog, contact messages, notification settings, cache |
| Files module | Chunked uploads, thumbnails, asset picker iframe (`Modules/Files`) |

Payments are **not** implemented yet (orders are placed without an online payment step).
See `ECOMMERCE_FEATURE_ANALYSIS.md` for the feature gap analysis and roadmap ideas.

### Key packages

| Package | Purpose |
|---|---|
| `spatie/laravel-permission` | Admin roles & permissions (RBAC) |
| `laravel/sanctum` | API tokens (installed; API surface is minimal) |
| `laravel/boost` (dev) | AI agent MCP server + guidelines (`CLAUDE.md`, `.junie/`) |
| `laravel/pint` (dev) | Code style |
| `phpunit/phpunit` (dev) | Tests |
| Vite 5 | Front-end asset bundling |

---

## 2. Local setup

```bash
git clone <repo> && cd pure_and_preloved
composer install
npm install
cp .env.example .env
php artisan key:generate
# configure DB_* in .env (MySQL), then:
php artisan migrate --seed
php artisan storage:link   # if serving uploaded assets from public storage
npm run dev                # or: npm run build
php artisan serve
```

Seeding (`DatabaseSeeder`) runs:

1. `AdminSeeder` — creates `admin@gmail.com` / `1234567890` **only if no admin exists**.
   Change this password immediately on any shared or production environment.
2. `RolesAndPermissionsSeeder` — idempotent; creates all permissions, the
   `Super Admin` role, and assigns it to every existing admin.

Admin login: `/admin/login`. Customer login: `/login`.

### Project-specific `.env` keys

| Key | Default | Effect |
|---|---|---|
| `CURRENCY` | `GBP` | Store currency; symbol map in `config/currency.php` (USD, GBP, BDT, EUR, INR). Unknown codes render as `CODE 10.00`. |
| `SEO_INDEXING` | `false` | `true` → `index, follow` meta + permissive `robots.txt`; `false` → `noindex, nofollow`. Keep `false` outside production. |
| `QUEUE_CONNECTION` | `sync` | Emails are dispatched as jobs; set `database` + run a worker in production. |
| `MAIL_*` | — | Required for verification, set-password, order and notification emails. |

---

## 3. Project structure

```
app/
  Console/Commands/MakeModuleCommand.php   # php artisan make:module {Name}
  Enum/                                    # General, NotificationType, Role (legacy int roles)
  Exceptions/InsufficientStockException.php
  Http/Controllers/
    Admin/      # admin panel (resource controllers per section)
    Admin/Auth/ # admin login / forgot / set password
    Public/     # storefront: Home (cart/wishlist/checkout), Product, Blog, Page, Contact, Sitemap
    User/       # customer auth + "My Orders"
  Http/Requests/{Admin,Auth}/              # form request validation
  Jobs/                                    # queued emails
  Mail/                                    # mailables
  Models/
  Notifications/                           # admin notifications (mail + web)
  Repositories/ (+ Interfaces/)            # User & AdminProfile repositories
  Services/                                # business logic (auth, tokens, admin, notifications)
  Support/                                 # AdminAccess (RBAC registry) + *Cache helpers
  helpers.php                              # global helpers (autoloaded)
Modules/
  Files/                                   # self-contained module (routes, views, migrations, provider)
routes/
  web.php    # storefront + customer account
  admin.php  # admin panel, prefixed /admin
  api.php
resources/views/{admin,public,user,email,partials}
```

### Conventions

- **Controllers stay thin**; put logic in `app/Services`, data access in `app/Repositories`.
- **Validation** lives in Form Requests (`app/Http/Requests`).
- **Emails** are sent via jobs in `app/Jobs` (never `Mail::send` inline in controllers).
- Run `vendor/bin/pint --dirty` before committing.

### Global helpers (`app/helpers.php`)

| Helper | Returns |
|---|---|
| `currency_symbol()` | Symbol for the configured currency |
| `currency($amount, $decimals = 2)` | Formatted price with symbol |
| `admin_can($permission)` | Whether the logged-in admin has a permission (use in Blade/sidebar) |
| `seo_robots()` | `index, follow` or `noindex, nofollow` based on `SEO_INDEXING` |

---

## 4. Authentication

Two separate session guards (`config/auth.php`):

| Guard | Model | Routes | Home |
|---|---|---|---|
| `web` | `App\Models\User` | `/login`, `/registration`, `/account/*` | `/dashboard` |
| `admin` | `App\Models\Admin` | `/admin/login`, `/admin/*` | `/admin/dashboard` |

- Customers must verify email (`verified` middleware on `/dashboard`).
- Set-password and verification flows use token tables (`user_access_tokens`,
  `admin_access_tokens`) handled by `UserAccessTokenService` / `AdminAccessTokenService`.
- New admins receive a set-password email; admins have a `status` column.

---

## 5. Admin RBAC (roles & permissions)

Built on Spatie Permission, scoped to the **`admin` guard**.

**Single source of truth:** `app/Support/AdminAccess.php`. Each admin module maps to one
permission (`manage users`, `manage orders`, `manage blog`, …). That list feeds the seeder,
the role form checkboxes, route middleware, and sidebar visibility.

- `Super Admin` role bypasses every check via `Gate::before` in `AuthServiceProvider`.
- Routes are guarded in `routes/admin.php` with
  `->middleware('permission:manage orders,admin')`.
- Sidebar/UI checks use `admin_can('manage orders')`.
- Always available to any logged-in admin: dashboard, own profile, notifications.

### Adding a new admin module

1. Add an entry to `AdminAccess::modules()`.
2. Wrap its routes in `routes/admin.php` with `permission:<permission>,admin`.
3. Guard its sidebar link with `admin_can()`.
4. Run `php artisan db:seed --class=RolesAndPermissionsSeeder` (locally and on deploy).
5. Extend `tests/Feature/AdminRbacTest.php`.

---

## 6. Routing notes

Route files are loaded by `RouteServiceProvider` in this order: `api.php` → `admin.php`
(prefix `/admin`) → `web.php`. Modules register their own routes in their service provider.

Order matters in `web.php`:

- `/sitemap.xml` and `/robots.txt` are declared first.
- `/product/{gender}/{category}/{product}` is the dynamic product route.
- **`/{slug}` (CMS pages) is a catch-all and must remain the last route.** Add every new
  storefront route above it, or it will be shadowed.
- `/terms-and-conditions` 301-redirects to the CMS `terms` page.

---

## 7. Domain notes

### Catalog
- Categories are hierarchical per gender (`women`, `man`, `unisex`) with `sort_order`
  (drag-reorder via `categories.update_order`). Products belong to many categories.
- Products carry jewelry attributes (material, carat, condition), `stock`, `is_featured`,
  thumbnail and meta image (assets from the Files module).

### Featured products
- Admin → **Featured Products** (`FeaturedProductController`) has two tabs:
  **Featured list** (drag-and-drop / Top / Bottom ordering, autosaved; bulk remove; Live /
  Inactive / Out-of-stock badges) and **Add products** (search by name/SKU, filter by
  category incl. children, status, stock; 25/50/100 per page; bulk add).
- Stored as `products.is_featured` + `products.featured_order` (1..n, no gaps). Always
  query with `Product::featured()` to get the admin order.
- Bulk add appends to the end; removal re-sequences the rest. `reorder` must receive the
  full current featured id list, otherwise it returns 409 (stale page).
- Each homepage **Featured Products Slider** section has a `limit` setting (1–24,
  default 10, `Product::featuredLimit()`); inactive products are skipped on the storefront.
- Every write calls `HomeCache::clear()`. Tests: `tests/Feature/FeaturedProductTest.php`.

### Cart, wishlist, checkout
- Cart and wishlist are **session-based** (`Public\HomeController`).
- `placeOrder` runs in a DB transaction and locks product rows (`lockForUpdate`) to
  decrement stock; shortages throw `InsufficientStockException`.
- After commit: `OrderConfirmationEmailJob` is dispatched and admins get `NewOrderPlaced`.
  Failures there are logged, not surfaced to the customer.
- Guest checkout is supported (`orders.user_id` is nullable). Orders are looked up by `reference`.

### Admin notifications
- `AdminNotificationService::notifyAdmins()` sends to all admins; each admin's channels
  (mail / web) come from `admin_notification_settings`.
- Current notifications: `NewOrderPlaced`, `ContactMessageReceived`, `BlogCommentReceived`.

### CMS, homepage, footer
- CMS pages (`pages` table) served at `/{slug}`.
- Homepage is built from ordered, toggleable `home_sections`.
- Footer content and social links are stored in `settings`.

### Blog
- Posts, categories, tags, and comments (comments require login; moderated by admin toggle).

---

## 8. Caching

Expensive storefront data is cached through small helpers in `app/Support`. **Any admin
write that affects cached data must call the matching `clear()`.**

| Helper | Caches | Clear when changing… |
|---|---|---|
| `HomeCache` | Home sections, featured products, home SEO | Home sections, products (featured) |
| `ShopCache` | Shop filter facets per gender (6h) | Products, categories |
| `MenuCache` | Category menu | Categories |
| `FooterCache` | Footer content (incl. recent blog posts) | Footer, blog posts |
| `Socials` | Social links | Social links |
| `SitemapCache` | `sitemap.xml` | Products, categories, pages, blog posts |

Admins can also flush via **Settings → Clear cache** (`CacheController`).
Tests run with `CACHE_DRIVER=array`.

---

## 9. Modules

`php artisan make:module Blog` scaffolds `Modules/Blog` (controllers, requests, models,
migrations, seeders, views, routes, services, provider, `module.json`). Then register
`Modules\Blog\Providers\BlogServiceProvider` in `config/app.php`. The `Modules\` namespace
is PSR-4 autoloaded from `composer.json`.

**Files module** (`Modules/Files`): admin-only asset manager under `/admin/files` —
chunked uploads, thumbnails, download/view/delete, and an iframe picker used by product,
category, and blog forms. Its migrations live in `Modules/Files/Database/migrations`.

---

## 10. Testing

```bash
php artisan test                         # all
php artisan test --filter=AdminRbacTest  # one class / method
```

- Feature tests use `DatabaseTransactions` against the **configured database** (sqlite
  in-memory is commented out in `phpunit.xml`), so a migrated local DB is required.
- Existing coverage: RBAC, blog, featured products, sitemap/robots, currency helper, sitemap cache.
- Known issue: 2 `CurrencyHelperTest` cases (config switching) currently fail; unrelated to recent features.
- Add or update a test with every behavior change.

---

## 11. Deployment (cPanel)

`.cpanel.yml` runs on deploy:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan queue:restart
```

Manual checklist for releases:

- [ ] `npm run build` and commit/upload `public/build` if the server can't build assets
- [ ] Run `php artisan db:seed --class=RolesAndPermissionsSeeder --force` when permissions change
- [ ] Production `.env`: `APP_ENV=production`, `APP_DEBUG=false`, real `MAIL_*`,
      `QUEUE_CONNECTION=database` with a running worker, `SEO_INDEXING=true` only when live
- [ ] Verify `/sitemap.xml` and `/robots.txt`

---

## 12. AI tooling

Laravel Boost is installed. `.mcp.json` registers the `laravel-boost` MCP server
(`php artisan boost:mcp`); `CLAUDE.md` and `.junie/guidelines.md` hold generated guidelines.
After upgrading Laravel or packages, run `php artisan boost:update` to refresh them.
Project-specific rules for agents belong in this file or outside the
`<laravel-boost-guidelines>` block in `CLAUDE.md`.

---

## 13. Changelog

Record notable developer-facing changes here (newest first).

| Date | Change |
|---|---|
| 2026-10-05 | Featured products redesign: explicit ordering, filterable picker, bulk add/remove, per-section limit |
| 2026-10-05 | Installed Laravel Boost; created this document |
| — | Admin RBAC via Spatie Permission (`AdminAccess`) |
| — | Dynamic sitemap, SEO-aware robots.txt, `SEO_INDEXING` switch |
