<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $body = <<<'HTML'
<p class="lead" style="font-size:1.2rem;line-height:1.7;">
    At <strong>Pure &amp; Preloved</strong> we give beautiful jewellery a second life — carefully sourced,
    authenticated and British hallmarked, then passed on to people who will treasure it next.
</p>

<div style="background:#f0fdfa;border-left:4px solid #0f766e;border-radius:.5rem;padding:1.25rem 1.5rem;margin:1.75rem 0;">
    <h2 style="color:#0f766e;font-size:1.35rem;font-weight:700;margin:0 0 .5rem;">Our Story</h2>
    <p style="margin:0;">
        Founded in Lincoln, United Kingdom, Pure &amp; Preloved began with a simple belief: that pre-owned and
        vintage jewellery deserves to be celebrated, not forgotten. Every piece in our collection is chosen by
        hand, checked for quality, and offered with honest descriptions and clear photography — so you always
        know exactly what you are buying.
    </p>
</div>

<h2 class="fw-bold mt-5 mb-4" style="font-size:1.6rem;">Why choose Pure &amp; Preloved</h2>
<div class="row">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="text-center p-4 h-100" style="border:1px solid #e2e8f0;border-radius:.75rem;">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                <i class="fa fa-certificate" style="font-size:24px;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;">British Hallmarked</h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">Every item is authenticated and British hallmarked for complete peace of mind.</p>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="text-center p-4 h-100" style="border:1px solid #e2e8f0;border-radius:.75rem;">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                <i class="fa fa-leaf" style="font-size:24px;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;">Sustainable Choice</h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">Preloved jewellery is a beautiful, eco-conscious alternative to buying new.</p>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="text-center p-4 h-100" style="border:1px solid #e2e8f0;border-radius:.75rem;">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                <i class="fa fa-truck" style="font-size:24px;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;">Free UK Delivery</h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">Free nationwide delivery via Royal Mail and Evri on every order.</p>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="text-center p-4 h-100" style="border:1px solid #e2e8f0;border-radius:.75rem;">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                <i class="fa fa-gem" style="font-size:24px;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;">Hand Curated</h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">Each piece is personally selected and inspected before it reaches you.</p>
        </div>
    </div>
</div>

<div class="row text-center mt-4" style="background:#0f766e;border-radius:.75rem;color:#ffffff;margin-left:0;margin-right:0;">
    <div class="col-6 col-md-3 py-4">
        <div style="font-size:1.7rem;font-weight:700;">100%</div>
        <div style="opacity:.85;font-size:.85rem;">British Hallmarked</div>
    </div>
    <div class="col-6 col-md-3 py-4">
        <div style="font-size:1.7rem;font-weight:700;">Free</div>
        <div style="opacity:.85;font-size:.85rem;">UK Delivery</div>
    </div>
    <div class="col-6 col-md-3 py-4">
        <div style="font-size:1.7rem;font-weight:700;">7 Days</div>
        <div style="opacity:.85;font-size:.85rem;">Easy Exchanges</div>
    </div>
    <div class="col-6 col-md-3 py-4">
        <div style="font-size:1.7rem;font-weight:700;">1-of-1</div>
        <div style="opacity:.85;font-size:.85rem;">Unique Pieces</div>
    </div>
</div>

<div class="text-center mt-5">
    <h2 class="fw-bold mb-2" style="font-size:1.5rem;">Discover your next treasure</h2>
    <p class="text-muted mb-4">Explore our curated collection of preloved gold, diamond and silver jewellery.</p>
    <a href="shop" style="display:inline-block;background:#0f766e;color:#ffffff;padding:.7rem 1.9rem;border-radius:.5rem;font-weight:600;text-decoration:none;">Shop the Collection</a>
</div>

<hr class="my-5">

<div class="text-center">
    <h3 style="font-size:1.15rem;font-weight:700;">Get in touch</h3>
    <p class="mb-0 text-muted">
        Pure and Preloved Limited · Dixon Street, Lincoln, United Kingdom<br>
        Email: <a href="mailto:support@pureandpreloved.co.uk" style="color:#0f766e;">support@pureandpreloved.co.uk</a> · Phone: +44 7396 823194
    </p>
</div>
HTML;

        DB::table('pages')
            ->where('slug', 'about-us')
            ->update([
                'title' => 'About Us',
                'body' => $body,
                'meta_title' => 'About Pure & Preloved | Preloved & Vintage Jewellery',
                'meta_description' => 'Pure & Preloved gives pre-owned and vintage jewellery a second life — authenticated, British hallmarked and hand-curated, with free UK delivery.',
                'status' => true,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'about-us')
            ->update([
                'body' => '<p>Welcome to <strong>Pure and Preloved Limited</strong>.</p>',
                'updated_at' => now(),
            ]);
    }
};
