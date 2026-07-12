<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Socials;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocialLinkController extends Controller
{
    public function edit()
    {
        $links = Setting::getJson('social_links', []);
        $platforms = Socials::PLATFORMS;

        return view('admin.sections.social.form', compact('links', 'platforms'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'links' => 'nullable|array',
            'links.*.platform' => ['required_with:links.*.url', Rule::in(array_keys(Socials::PLATFORMS))],
            'links.*.url' => 'nullable|string|max:2048',
        ]);

        $links = [];
        foreach ((array) ($validated['links'] ?? []) as $link) {
            $url = trim((string) ($link['url'] ?? ''));
            $platform = $link['platform'] ?? null;

            if ($url === '' || !isset(Socials::PLATFORMS[$platform])) {
                continue;
            }

            $links[] = ['platform' => $platform, 'url' => $url];
        }

        Setting::set('social_links', json_encode($links, JSON_UNESCAPED_SLASHES));

        Socials::clearCache();

        return back()->with('success', 'Social links saved. They apply everywhere social icons are shown.');
    }
}
