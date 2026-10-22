<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return array<string, array{0: string}>
     */
    public static function layoutRoutes(): array
    {
        return [
            'storefront home' => ['home'],
            'customer login' => ['login'],
            'admin login' => ['admin.login'],
        ];
    }

    /**
     * @dataProvider layoutRoutes
     */
    public function test_layouts_link_the_shared_favicon_set(string $routeName): void
    {
        $response = $this->get(route($routeName));

        $response->assertOk();
        $response->assertSee(asset('assets/images/favicon/favicon.ico'), false);
        $response->assertSee(asset('assets/images/favicon/apple-touch-icon.png'), false);
        $response->assertSee(asset('assets/images/favicon/site.webmanifest'), false);
        $response->assertDontSee('panel/assets/favicon', false);
    }

    public function test_every_linked_favicon_file_exists(): void
    {
        $directory = public_path('assets/images/favicon');

        foreach (['favicon.ico', 'favicon-16x16.png', 'favicon-32x32.png', 'apple-touch-icon.png', 'site.webmanifest'] as $file) {
            $this->assertFileExists("{$directory}/{$file}");
        }

        $this->assertGreaterThan(0, filesize(public_path('favicon.ico')));
    }

    public function test_web_manifest_icons_resolve_to_existing_files(): void
    {
        $manifest = json_decode(file_get_contents(public_path('assets/images/favicon/site.webmanifest')), true);

        $this->assertSame('Pure and Preloved', $manifest['name']);
        $this->assertNotEmpty($manifest['icons']);

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
        }
    }
}
