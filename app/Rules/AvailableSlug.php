<?php

namespace App\Rules;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;

/**
 * Ensures a page slug does not conflict with another page OR with any other
 * URL on the site (a real registered route such as /shop, /cart, /login, …).
 */
class AvailableSlug implements ValidationRule
{
    public function __construct(private ?int $ignoreId = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = (string) $value;

        // 1) Conflict with another CMS page.
        $usedByPage = Page::where('slug', $slug)
            ->when($this->ignoreId, fn ($q) => $q->where('id', '!=', $this->ignoreId))
            ->exists();

        if ($usedByPage) {
            $fail('This URL is already used by another page. Please choose a different slug.');
            return;
        }

        // 2) Conflict with an existing site route (its first URL segment).
        if (in_array($slug, $this->reservedSegments(), true)) {
            $fail('"' . $slug . '" is reserved by another part of the site. Please choose a different slug.');
        }
    }

    /**
     * The first URL segment of every static (non-dynamic) registered route,
     * plus a few framework/path reservations. The page catch-all itself has a
     * dynamic first segment ("{slug}") so it is naturally excluded.
     *
     * @return array<int, string>
     */
    private function reservedSegments(): array
    {
        $reserved = [];

        foreach (Route::getRoutes() as $route) {
            $first = explode('/', $route->uri())[0] ?? '';

            if ($first === '' || str_contains($first, '{')) {
                continue;
            }

            $reserved[strtolower($first)] = true;
        }

        foreach (['admin', 'api', 'page', 'pages', 'storage'] as $extra) {
            $reserved[$extra] = true;
        }

        return array_keys($reserved);
    }
}
