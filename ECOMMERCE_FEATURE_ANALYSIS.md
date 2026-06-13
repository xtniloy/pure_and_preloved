# Jewelry Ecommerce — Feature & Implementation Analysis

> Scope note: Payment gateway integration is intentionally excluded (planned for the future).
> One correction vs. the raw scan: the price-range filter uses Eloquent's `whereBetween`, which
> binds parameters — so it is **not** a SQL injection risk, only a missing input-validation issue.

---

## 1. What a jewelry ecommerce site needs — and what you have

Jewelry has some category-specific needs (high-value items, trust signals, certification, sizing).
Here's the standard feature set scored against the current build:

| Feature | Status | Notes |
|---|---|---|
| Product catalog + categories | ✅ Have | Hierarchical (gender + 2 levels), multi-category |
| Jewelry attributes (material, carat, weight, condition) | ✅ Have | Good — already jewelry-aware |
| Product detail + gallery + quick view | ✅ Have | Slug-based, SEO-friendly |
| Shop listing: search, filter, sort, pagination | ✅ Have | Solid |
| Featured products | ✅ Have | |
| Cart | ⚠️ Have (session only) | Not persisted — see §3 |
| Wishlist | ⚠️ Have (session only) | Lost on logout/device change |
| Checkout + orders + order items | ✅ Have | Guest checkout supported |
| Shipping methods | ✅ Have | |
| Customer auth + email verification | ✅ Have | |
| Admin panel (products/orders/users/categories) | ✅ Have | Comprehensive |
| File/asset manager | ✅ Have | The module recently improved |
| **Stock / inventory** | ❌ Missing | **Critical for jewelry — often 1-of-a-kind** |
| **Product variants (ring size, chain length)** | ❌ Missing | Essential for jewelry |
| **Reviews & ratings** | ❌ Missing | Key trust signal |
| **Customer order history** | ❌ Missing | Customers can't see past orders |
| **Order email notifications** | ❌ Missing | No confirmation emails |
| **Coupons / discounts** | ❌ Missing | Standard marketing need |
| **CMS pages (About, Care, Returns)** | ❌ Missing | Only static Terms page |
| Trust/jewelry signals (certificates, hallmark, warranty) | ❌ Missing | Important for high-value items |
| Related / "you may also like" products | ❓ Unclear | Worth confirming |

---

## 2. Missing features — prioritized

### Must-have (the site is functionally incomplete without these)

1. **Inventory/stock tracking** — There is *no* quantity-on-hand field anywhere, and placing an order
   does **not** decrement anything. For jewelry (frequently single-piece stock), this means the same
   unique ring can be sold to 10 people. This is the single biggest gap. Needs: a `stock`/`quantity`
   column on products (or per-variant), stock validation at add-to-cart and at `placeOrder`, and
   decrement-on-order inside the order transaction.

2. **Customer order history** — `User hasMany Order` exists, but there's no customer-facing "My Orders"
   page (only the admin can see orders). Customers who register can't review what they bought.

3. **Order confirmation / status emails** — A mailer is already set up (verification + password reset
   emails exist), but placing an order and changing its status send nothing. Customers get no confirmation.

### Should-have (expected on a real store)

4. **Product variants** — ring size, chain length, metal option. Currently a product is one fixed
   SKU/price. Most jewelry stores need at least size selection.
5. **Reviews & ratings** — major conversion/trust driver; entirely absent.
6. **Persistent cart & wishlist** — see §3; right now both vanish.
7. **Coupons/discounts** — no discount mechanism at all.
8. **CMS/static pages** — Returns policy, Shipping info, About, Care guide. High-value buyers expect these.

### Nice-to-have

Related products, recently viewed, certificate/authenticity display, low-stock urgency,
abandoned-cart recovery, basic sales reporting.

---

## 3. What's wrongly / riskily developed

These are existing things that work but are built in a way that will cause bugs or data problems.

### A. Cart & wishlist are session-only (data loss)
Stored only in the PHP session, no DB table. Consequences: a logged-in customer loses their cart when
the session expires, they switch device, or they log out. For a considered purchase like jewelry
(people browse for days), this is a real revenue leak.
→ Move to DB-backed cart/wishlist keyed to the user (with a session cart for guests that merges on login).

### B. `placeOrder()` trusts the items/quantities from the request without validating them
`HomeController::placeOrder` reads `$request->input('items', [])` and uses those quantities directly —
no validation that the IDs exist, that quantities are positive integers, or (critically) that stock is
available. Prices are correctly re-fetched from the DB (good — no price tampering), but quantities are
attacker-controllable. Someone can post `quantity: -5` or `99999`.
→ Validate the items array structure and, once inventory exists, clamp to available stock.

### C. `updateCart()` doesn't validate product IDs
`quantities.*` is validated as integer ≥ 0, but the product-ID keys aren't checked against the DB, so
arbitrary keys get written into the session cart. Low severity, but sloppy state.

### D. Order placement isn't wrapped in a DB transaction
The order row and its `order_items` are created in separate `create()` calls with no `DB::transaction()`.
If item insertion fails midway, you get an orphan order with partial/no items.
→ Wrap the whole `placeOrder` write in a transaction (this also becomes essential once stock decrement
is added).

### E. Product images: JSON array of asset IDs with no referential integrity
`products.images` is a JSON array of asset IDs, read back via `getAssetsAttribute()` →
`Asset::whereIn(...)`. There's no pivot table, so deleting an asset leaves **dangling IDs** in products,
and you can't query "which products use this image." `thumbnail_image_id`/`meta_image_id` are proper FKs,
but the gallery isn't. This is workable but fragile — at minimum, guard the file-delete path against
assets still referenced by products, and consider a `product_asset` pivot if you want integrity + ordering.
(Note this also ties into the recent file-manager work.)

### F. Order status is a free string
`status` is a `string` column with app-only `in:` validation. Fine for now, but it lives as hardcoded
arrays in two places in `OrderController`.
→ Centralize into an enum/constant (the codebase already uses an `App\Enum\General` enum elsewhere —
same pattern) so admin UI, validation, and any future emails share one source of truth.

### G. Price-range filter has no input validation
`whereBetween('price', [$request->min_price, $request->max_price])` — not SQL injection (Eloquent binds
these), but non-numeric input will error or behave oddly.
→ Add `numeric` validation on `min_price`/`max_price`.

### H. Empty `Modules/Gallery`
Scaffolding only — no migrations/models/controllers. Either build it or delete it so it's not dead weight.

---

## 4. Recommended order to tackle this

1. **Inventory tracking + stock validation + DB transaction in `placeOrder`** (fixes B, D, and the #1
   missing feature together).
2. **Order confirmation emails + customer order history** (completes the purchase loop).
3. **Persistent cart/wishlist** (fixes A — stops losing sales).
4. **Product variants** (jewelry sizing) — bigger schema change, plan it before it's load-bearing.
5. **Reviews, coupons, CMS pages** — growth/trust features once the core is solid.
6. **Cleanups**: status enum (F), filter validation (G), image-delete guard (E), remove/finish Gallery
   module (H).

**Suggested first step:** #1 (inventory + transactional checkout) — it's the most damaging gap and a
contained change: a migration + stock validation/decrement in `placeOrder`.
