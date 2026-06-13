<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        $now = now();

        $termsBody = <<<'HTML'
<p class="text-muted mb-4">Last Updated: 01 January 2026</p>

<p>
    Welcome to <strong>Pure and Preloved Limited</strong>. By purchasing from us, you agree to the following Terms &amp;
    Conditions. These policies ensure clarity, fairness, and protection for both our customers and our business.
</p>

<hr class="my-4">

<h4 class="mt-4">1. Product Condition &amp; Descriptions</h4>
<ul>
    <li>All items are <strong>pre-owned or vintage</strong>, unless stated otherwise.</li>
    <li>Products are sold strictly on a <strong>“sold as seen”</strong> basis.</li>
    <li>We provide detailed photos, descriptions, and measurements.</li>
    <li>
        Minor variations may occur due to:
        <ul>
            <li>Manual measurement limitations</li>
            <li>Tool variations</li>
            <li>Settings that obscure exact dimensions</li>
        </ul>
    </li>
    <li>These variations do not qualify as misrepresentation.</li>
    <li>Colours may vary slightly due to lighting or screen differences.</li>
</ul>

<h4 class="mt-4">2. Postage &amp; Delivery</h4>
<ul>
    <li>Orders are shipped via <strong>Royal Mail or Evri</strong>.</li>
    <li>Postage costs are <strong>non-refundable</strong> unless we made an error.</li>
    <li>Delivery times are the responsibility of the courier once dispatched.</li>
    <li>We do <strong>not offer international shipping</strong>.</li>
</ul>

<h4 class="mt-4">3. Refunds, Returns &amp; Exchanges</h4>

<h5 class="mt-3">3.1 Refund Policy</h5>
<ul>
    <li>No refunds on preloved items.</li>
    <li>
        This includes:
        <ul>
            <li>Change of mind</li>
            <li>Buyer error</li>
            <li>Minor variations in size, weight, or colour</li>
            <li>Normal wear of preloved jewellery</li>
        </ul>
    </li>
</ul>

<h5 class="mt-3">3.2 Exchange Policy</h5>
<ul>
    <li>Exchanges allowed within <strong>7 days</strong> of receiving the item.</li>
    <li>Items are unique; identical replacements are unlikely.</li>
    <li>
        You must select:
        <ul>
            <li>Item of equal value, or</li>
            <li>Higher value (pay the difference)</li>
        </ul>
    </li>
</ul>

<h5 class="mt-3">3.3 Return Conditions</h5>
<ul>
    <li>Item must be returned in original condition.</li>
    <li>Return shipping is paid by the buyer.</li>
    <li>We are not responsible for lost or damaged returns.</li>
    <li>Use tracked &amp; insured shipping.</li>
</ul>

<h4 class="mt-4">4. Hallmarking &amp; Certification</h4>
<ul>
    <li>All items are <strong>British hallmarked</strong>.</li>
    <li>No certificates or valuations are provided.</li>
    <li>Independent valuations are at buyer’s discretion.</li>
</ul>

<h4 class="mt-4">5. Liability</h4>
<ul>
    <li>
        We are not liable for:
        <ul>
            <li>Allergic reactions</li>
            <li>Improper handling or cleaning damage</li>
            <li>Normal wear and tear</li>
        </ul>
    </li>
    <li>Customers should insure jewellery after purchase.</li>
    <li>No liability for third-party repairs or alterations.</li>
</ul>

<h5 class="mt-3">5.1 Statutory Rights</h5>
<p>
    These Terms do not affect your rights under the
    <strong>Consumer Rights Act 2015</strong>.
</p>

<h4 class="mt-4">6. Contact Information</h4>
<p class="mb-0">
    <strong>Pure and Preloved Limited</strong><br>
    Company Number: 16918159<br>
    Email: <a href="mailto:support@pureandpreloved.co.uk">support@pureandpreloved.co.uk</a><br>
    Phone: +44 7396 823194
</p>
HTML;

        $aboutBody = <<<'HTML'
<p>Welcome to <strong>Pure and Preloved Limited</strong> — your destination for carefully curated pre-owned and vintage jewellery.</p>
<p>Edit this page from the admin panel to tell your story: who you are, what makes your pieces special, and why customers can buy from you with confidence.</p>
HTML;

        $shippingBody = <<<'HTML'
<h4>Delivery</h4>
<ul>
    <li>Orders are shipped via <strong>Royal Mail or Evri</strong>.</li>
    <li>Free nationwide delivery across the UK.</li>
    <li>We do not currently offer international shipping.</li>
</ul>
<p>Edit this page from the admin panel to add full shipping details, timescales and costs.</p>
HTML;

        $returnsBody = <<<'HTML'
<h4>Returns &amp; Exchanges</h4>
<ul>
    <li>Exchanges are allowed within <strong>7 days</strong> of receiving your item.</li>
    <li>Items must be returned in their original condition.</li>
    <li>Return shipping is paid by the buyer.</li>
</ul>
<p>Edit this page from the admin panel to add your full returns policy.</p>
HTML;

        $careBody = <<<'HTML'
<h4>Caring for your jewellery</h4>
<ul>
    <li>Store pieces separately to avoid scratches.</li>
    <li>Keep away from perfumes, lotions and water where possible.</li>
    <li>Clean gently with a soft, dry cloth.</li>
</ul>
<p>Edit this page from the admin panel to add a complete care guide.</p>
HTML;

        DB::table('pages')->insert([
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'body' => $termsBody,
                'meta_title' => 'Terms & Conditions',
                'meta_description' => 'Terms and conditions for purchasing from Pure and Preloved Limited.',
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'body' => $aboutBody,
                'meta_title' => 'About Us',
                'meta_description' => null,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Shipping Information',
                'slug' => 'shipping-information',
                'body' => $shippingBody,
                'meta_title' => 'Shipping Information',
                'meta_description' => null,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Returns Policy',
                'slug' => 'returns-policy',
                'body' => $returnsBody,
                'meta_title' => 'Returns Policy',
                'meta_description' => null,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Jewellery Care Guide',
                'slug' => 'care-guide',
                'body' => $careBody,
                'meta_title' => 'Jewellery Care Guide',
                'meta_description' => null,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
