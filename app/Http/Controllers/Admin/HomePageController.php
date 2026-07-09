<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomePageController extends Controller
{
    /**
     * Tags admins may use in heading/title fields (e.g. <strong>WE ALSO RECOMMEND</strong>).
     */
    private const ALLOWED_TAGS = '<strong><b><em><i><u><br><span>';

    public function index()
    {
        // Fixed sections (hero) are managed on their own page, not in this list.
        $sections = HomeSection::query()
            ->orderBy('position')
            ->orderBy('id')
            ->get()
            ->reject->isFixed()
            ->values();

        $seo = [
            'meta_title' => Setting::get('home_meta_title'),
            'meta_description' => Setting::get('home_meta_description'),
            'meta_keywords' => Setting::get('home_meta_keywords'),
            'meta_image_id' => Setting::get('home_meta_image_id'),
        ];

        $addableTypes = HomeSection::addableTypes();

        return view('admin.sections.homepage.index', compact('sections', 'seo', 'addableTypes'));
    }

    /**
     * Standalone editor for the fixed hero slider (own sidebar menu item).
     */
    public function hero()
    {
        $section = HomeSection::query()->where('type', 'hero_slider')->firstOrFail();

        return view('admin.sections.homepage.form', [
            'type' => $section->type,
            'section' => $section,
            'standalone' => true,
        ]);
    }

    public function create(string $type)
    {
        abort_unless($this->isAddableType($type), 404);

        return view('admin.sections.homepage.form', ['type' => $type]);
    }

    public function store(Request $request)
    {
        $type = (string) $request->input('type');
        abort_unless($this->isAddableType($type), 404);

        $validated = $request->validate($this->rules($type));

        HomeSection::create([
            'type' => $type,
            'title' => $validated['title'],
            'data' => $this->buildData($type, $request),
            'position' => (int) HomeSection::query()->max('position') + 1,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.homepage.index')->with('success', 'Section added to the homepage.');
    }

    public function edit(HomeSection $section)
    {
        abort_unless($section->isEditable(), 404);

        if ($section->isFixed()) {
            return redirect()->route('admin.homepage.hero');
        }

        return view('admin.sections.homepage.form', ['type' => $section->type, 'section' => $section]);
    }

    public function update(Request $request, HomeSection $section)
    {
        abort_unless($section->isEditable(), 404);

        $validated = $request->validate($this->rules($section->type));

        $section->update([
            'title' => $validated['title'],
            'data' => $this->buildData($section->type, $request),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($section->isFixed()) {
            return redirect()->route('admin.homepage.hero')->with('success', 'Hero slider updated.');
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
    }

    public function toggle(HomeSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        return back()->with('success', $section->is_active ? 'Section is now visible.' : 'Section hidden from the homepage.');
    }

    public function destroy(HomeSection $section)
    {
        abort_unless($section->isDeletable(), 403);

        $section->delete();

        return back()->with('success', 'Section removed from the homepage.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:home_sections,id',
        ]);

        $sections = HomeSection::query()
            ->whereIn('id', $validated['ids'])
            ->get()
            ->keyBy('id');

        $position = 1; // position 0 is reserved for the fixed hero
        foreach ($validated['ids'] as $id) {
            $section = $sections[$id] ?? null;

            if (!$section || $section->isFixed()) {
                continue;
            }

            $section->update(['position' => $position++]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_image_id' => 'nullable|integer|exists:assets,id',
        ]);

        Setting::set('home_meta_title', $validated['meta_title'] ?? null);
        Setting::set('home_meta_description', $validated['meta_description'] ?? null);
        Setting::set('home_meta_keywords', $validated['meta_keywords'] ?? null);
        Setting::set('home_meta_image_id', $validated['meta_image_id'] ?? null);

        return back()->with('success', 'Homepage SEO settings saved.');
    }

    private function isAddableType(string $type): bool
    {
        return isset(HomeSection::TYPES[$type]) && HomeSection::TYPES[$type]['addable'];
    }

    private function rules(string $type): array
    {
        $rules = ['title' => 'required|string|max:255'];

        switch ($type) {
            case 'hero_slider':
                return $rules + [
                    'slides' => 'required|array|min:1',
                    'slides.*.image_id' => 'nullable|integer|exists:assets,id',
                    'slides.*.image_url' => 'nullable|string|max:2048',
                    'slides.*.heading' => 'nullable|string|max:500',
                    'slides.*.subheading' => 'nullable|string|max:500',
                    'slides.*.button_text' => 'nullable|string|max:100',
                    'slides.*.button_url' => 'nullable|string|max:2048',
                ];

            case 'module_grid':
                return $rules + [
                    'heading' => 'nullable|string|max:500',
                    'subheading' => 'nullable|string|max:1000',
                    'background' => ['required', Rule::in(['white', 'grey'])],
                    'title_tag' => ['required', Rule::in(['h3', 'h4', 'h5', 'h6'])],
                    'items' => 'required|array|min:1',
                    'items.*.image_id' => 'nullable|integer|exists:assets,id',
                    'items.*.image_url' => 'nullable|string|max:2048',
                    'items.*.title' => 'nullable|string|max:500',
                    'items.*.subtitle' => 'nullable|string|max:500',
                    'items.*.button_text' => 'nullable|string|max:100',
                    'items.*.url' => 'nullable|string|max:2048',
                    'items.*.text_style' => ['nullable', Rule::in(['dark', 'light'])],
                ];

            case 'feature_strip':
                return $rules + [
                    'heading' => 'nullable|string|max:500',
                    'theme' => ['required', Rule::in(['light', 'dark'])],
                    'items' => 'required|array|min:1',
                    'items.*.image_id' => 'nullable|integer|exists:assets,id',
                    'items.*.image_url' => 'nullable|string|max:2048',
                    'items.*.title' => 'nullable|string|max:255',
                    'items.*.subtitle' => 'nullable|string|max:255',
                    'items.*.url' => 'nullable|string|max:2048',
                ];

            case 'full_banner':
                return $rules + [
                    'image_id' => 'nullable|integer|exists:assets,id',
                    'image_url' => 'nullable|string|max:2048',
                    'badge' => 'nullable|string|max:100',
                    'eyebrow' => 'nullable|string|max:255',
                    'heading' => 'required|string|max:500',
                    'subheading' => 'nullable|string|max:500',
                    'button_text' => 'nullable|string|max:100',
                    'button_url' => 'nullable|string|max:2048',
                    'theme' => ['required', Rule::in(['light', 'dark'])],
                ];

            case 'featured_products':
                return $rules + [
                    'heading' => 'required|string|max:500',
                ];

            case 'text_columns':
                return $rules + [
                    'heading' => 'nullable|string|max:500',
                    'columns' => 'required|array|min:1',
                    'columns.*' => 'nullable|string|max:2000',
                ];
        }

        return $rules;
    }

    private function buildData(string $type, Request $request): ?array
    {
        switch ($type) {
            case 'hero_slider':
                $slides = [];
                foreach ((array) $request->input('slides', []) as $slide) {
                    if (!is_array($slide) || !$this->rowHasContent($slide)) {
                        continue;
                    }

                    $slides[] = $this->imagePair($slide) + [
                        'heading' => $this->cleanText($slide['heading'] ?? null),
                        'subheading' => $this->cleanText($slide['subheading'] ?? null),
                        'button_text' => $this->cleanText($slide['button_text'] ?? null),
                        'button_url' => $this->cleanText($slide['button_url'] ?? null),
                    ];
                }

                return ['slides' => $slides];

            case 'module_grid':
                $items = [];
                foreach ((array) $request->input('items', []) as $item) {
                    if (!is_array($item) || !$this->rowHasContent($item)) {
                        continue;
                    }

                    $items[] = $this->imagePair($item) + [
                        'title' => $this->cleanHtml($item['title'] ?? null),
                        'subtitle' => $this->cleanText($item['subtitle'] ?? null),
                        'button_text' => $this->cleanText($item['button_text'] ?? null),
                        'url' => $this->cleanText($item['url'] ?? null),
                        'text_style' => ($item['text_style'] ?? 'dark') === 'light' ? 'light' : 'dark',
                    ];
                }

                return [
                    'heading' => $this->cleanHtml($request->input('heading')),
                    'subheading' => $this->cleanHtml($request->input('subheading')),
                    'background' => $request->input('background') === 'grey' ? 'grey' : 'white',
                    'title_tag' => in_array($request->input('title_tag'), ['h3', 'h4', 'h5', 'h6'], true) ? $request->input('title_tag') : 'h4',
                    'image_rounded' => $request->boolean('image_rounded'),
                    'items' => $items,
                ];

            case 'feature_strip':
                $items = [];
                foreach ((array) $request->input('items', []) as $item) {
                    if (!is_array($item) || !$this->rowHasContent($item)) {
                        continue;
                    }

                    $items[] = $this->imagePair($item) + [
                        'title' => $this->cleanText($item['title'] ?? null),
                        'subtitle' => $this->cleanText($item['subtitle'] ?? null),
                        'url' => $this->cleanText($item['url'] ?? null),
                    ];
                }

                return [
                    'heading' => $this->cleanHtml($request->input('heading')),
                    'theme' => $request->input('theme') === 'dark' ? 'dark' : 'light',
                    'items' => $items,
                ];

            case 'full_banner':
                return $this->imagePair($request->all()) + [
                    'badge' => $this->cleanText($request->input('badge')),
                    'eyebrow' => $this->cleanText($request->input('eyebrow')),
                    'heading' => $this->cleanHtml($request->input('heading')),
                    'subheading' => $this->cleanText($request->input('subheading')),
                    'button_text' => $this->cleanText($request->input('button_text')),
                    'button_url' => $this->cleanText($request->input('button_url')),
                    'theme' => $request->input('theme') === 'dark' ? 'dark' : 'light',
                ];

            case 'featured_products':
                return [
                    'heading' => $this->cleanHtml($request->input('heading')),
                ];

            case 'text_columns':
                $columns = [];
                foreach ((array) $request->input('columns', []) as $column) {
                    $column = $this->cleanText(is_string($column) ? $column : null);
                    if ($column !== null) {
                        $columns[] = $column;
                    }
                }

                return [
                    'heading' => $this->cleanHtml($request->input('heading')),
                    'columns' => $columns,
                ];
        }

        return null;
    }

    /**
     * A repeater row counts as content when any of its fields were filled in.
     */
    private function rowHasContent(array $row): bool
    {
        foreach ($row as $value) {
            if (is_string($value) && trim($value) !== '') {
                return true;
            }
        }

        return false;
    }

    private function imagePair(array $row): array
    {
        return [
            'image_id' => !empty($row['image_id']) ? (int) $row['image_id'] : null,
            'image_url' => !empty($row['image_url']) && is_string($row['image_url']) ? trim($row['image_url']) : null,
        ];
    }

    private function cleanHtml(?string $value): ?string
    {
        $value = trim(strip_tags((string) $value, self::ALLOWED_TAGS));

        return $value === '' ? null : $value;
    }

    private function cleanText(?string $value): ?string
    {
        $value = trim(strip_tags((string) $value));

        return $value === '' ? null : $value;
    }
}
