<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * Tags admins may use in the copyright line (e.g. a link to the site).
     */
    private const ALLOWED_TAGS = '<a><strong><b><em><i><span>';

    public function edit()
    {
        $footer = Setting::getJson('footer_content', []);

        return view('admin.sections.footer.form', compact('footer'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_text' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'copyright' => 'nullable|string|max:500',
            'info_links' => 'nullable|array',
            'info_links.*.label' => 'nullable|string|max:100',
            'info_links.*.url' => 'nullable|string|max:2048',
            'custom_links' => 'nullable|array',
            'custom_links.*.label' => 'nullable|string|max:100',
            'custom_links.*.url' => 'nullable|string|max:2048',
            'tag_links' => 'nullable|array',
            'tag_links.*.label' => 'nullable|string|max:100',
            'tag_links.*.url' => 'nullable|string|max:2048',
        ]);

        $infoLinks = $this->cleanLinks($validated['info_links'] ?? []);
        $customLinks = $this->cleanLinks($validated['custom_links'] ?? []);
        $tagLinks = $this->cleanLinks($validated['tag_links'] ?? []);

        $footer = [
            'about_text' => trim(strip_tags((string) ($validated['about_text'] ?? ''))) ?: null,
            'address' => trim(strip_tags((string) ($validated['address'] ?? ''))) ?: null,
            'email' => trim((string) ($validated['email'] ?? '')) ?: null,
            'phone' => trim((string) ($validated['phone'] ?? '')) ?: null,
            'info_links' => $infoLinks,
            'custom_links' => $customLinks,
            'tag_links' => $tagLinks,
            'copyright' => trim(strip_tags((string) ($validated['copyright'] ?? ''), self::ALLOWED_TAGS)) ?: null,
        ];

        Setting::set('footer_content', json_encode($footer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return back()->with('success', 'Footer content saved.');
    }

    /**
     * Keep rows that have a label; default empty URLs to '#'.
     */
    private function cleanLinks(array $links): array
    {
        $clean = [];

        foreach ($links as $link) {
            $label = trim(strip_tags((string) ($link['label'] ?? '')));
            if ($label === '') {
                continue;
            }

            $clean[] = [
                'label' => $label,
                'url' => trim((string) ($link['url'] ?? '')) ?: '#',
            ];
        }

        return $clean;
    }
}
