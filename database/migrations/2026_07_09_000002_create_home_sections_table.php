<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->json('data')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the builder with the content that was previously hard-coded in
        // resources/views/public/home/index.blade.php, in the same order.
        // Images reference the theme's static assets via image_url; when an
        // admin picks a file-manager image the section stores image_id instead.
        $sections = [
            [
                'type' => 'hero_slider',
                'title' => 'Hero Slider',
                'data' => [
                    'slides' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/slider-image/sample-1.webp',
                            'heading' => "Women\nBeautiful Jewellery",
                            'subheading' => 'Pure and Preloved.',
                            'button_text' => 'SHOP NOW',
                            'button_url' => '',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/slider-image/sample-1.webp',
                            'heading' => "Men's\nBeautiful Jewellery",
                            'subheading' => 'Pure and Preloved.',
                            'button_text' => 'SHOP NOW',
                            'button_url' => '',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/slider-image/sample-1.webp',
                            'heading' => "Women\nBeautiful Jewellery",
                            'subheading' => 'Pure and Preloved.',
                            'button_text' => 'SHOP NOW',
                            'button_url' => '',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'perks_strip',
                'title' => 'Perks Strip (Free Shipping / Returns / ...)',
                'data' => null,
            ],
            [
                'type' => 'module_grid',
                'title' => 'Tempest Nature Banners',
                'data' => [
                    'heading' => null,
                    'subheading' => null,
                    'background' => 'white',
                    'title_tag' => 'h5',
                    'image_rounded' => false,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/summersale-june.jpg',
                            'title' => 'TEMPEST NATURE',
                            'subtitle' => 'INSPIRED JEWELLERY',
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/summersale-june.jpg',
                            'title' => 'TEMPEST NATURE',
                            'subtitle' => 'INSPIRED JEWELLERY',
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'feature_strip',
                'title' => 'Featured Products Strip',
                'data' => [
                    'heading' => 'Featured Products',
                    'theme' => 'light',
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'BUY 2, GET 30% OFF',
                            'subtitle' => 'ON IMERAKI',
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/5.jpg',
                            'title' => 'BUY 2, GET 30% OFF',
                            'subtitle' => 'ON IMERAKI',
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/6.jpg',
                            'title' => 'BUY 2, GET 30% OFF',
                            'subtitle' => 'ON IMERAKI',
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/7.jpg',
                            'title' => 'BUY 2, GET 30% OFF',
                            'subtitle' => 'ON IMERAKI',
                            'url' => '#',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'featured_products',
                'title' => 'We Also Recommend For You',
                'data' => [
                    'heading' => '<strong>WE ALSO RECOMMEND</strong> FOR YOU',
                ],
            ],
            [
                'type' => 'full_banner',
                'title' => 'Shop All Brands Banner',
                'data' => [
                    'image_id' => null,
                    'image_url' => 'assets/images/banner-image/homepage-shopallbrands.jpg',
                    'badge' => null,
                    'eyebrow' => null,
                    'heading' => 'SHOP ALL BRANDS',
                    'subheading' => "A stunning range of women's jewellery",
                    'button_text' => 'Shop Now',
                    'button_url' => '#',
                    'theme' => 'light',
                ],
            ],
            [
                'type' => 'module_grid',
                'title' => 'Personalised Jewellery (Large Banners)',
                'data' => [
                    'heading' => 'PERSONALISED <strong>JEWELLERY</strong>',
                    'subheading' => 'Discover beautiful personalised jewellery with <strong>FREE</strong> engraving of your choice!',
                    'background' => 'white',
                    'title_tag' => 'h3',
                    'image_rounded' => false,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/EngravingAll.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/summersale-june.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'module_grid',
                'title' => 'Personalised Jewellery (Small Banners)',
                'data' => [
                    'heading' => null,
                    'subheading' => null,
                    'background' => 'white',
                    'title_tag' => 'h6',
                    'image_rounded' => false,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/homeengraving.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => 'WITH YOUR MESSAGE',
                            'button_text' => 'Shop',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/homeengraving.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => 'WITH YOUR MESSAGE',
                            'button_text' => 'Shop',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/homeengraving.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => 'WITH YOUR MESSAGE',
                            'button_text' => 'Shop',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/homeengraving.jpg',
                            'title' => 'YOUR PETS <strong>NOSE PRINT</strong>',
                            'subtitle' => 'WITH YOUR MESSAGE',
                            'button_text' => 'Shop',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'full_banner',
                'title' => 'Nomination Jewellery Banner',
                'data' => [
                    'image_id' => null,
                    'image_url' => 'assets/images/banner-image/homepage-nomo24.jpg',
                    'badge' => null,
                    'eyebrow' => 'NEW IN!',
                    'heading' => 'ENGRAVABLE NOMINATION JEWELLERY',
                    'subheading' => null,
                    'button_text' => 'Shop Now',
                    'button_url' => '#',
                    'theme' => 'dark',
                ],
            ],
            [
                'type' => 'feature_strip',
                'title' => 'Shop By Type',
                'data' => [
                    'heading' => 'SHOP BY TYPE',
                    'theme' => 'light',
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/5.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/6.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/7.jpg',
                            'title' => 'RING',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'module_grid',
                'title' => "Women's / Children's / Men's Jewellery",
                'data' => [
                    'heading' => null,
                    'subheading' => null,
                    'background' => 'white',
                    'title_tag' => 'h3',
                    'image_rounded' => true,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/woman.jpg',
                            'title' => "<strong>WOMEN'S</strong> <br> JEWELLERY",
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'light',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/christmas24-childrens.jpg',
                            'title' => "<strong>CHILDREN'S</strong> <br> JEWELLERY",
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/man.jpg',
                            'title' => "<strong>MEN'S</strong> <br> JEWELLERY",
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'light',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'full_banner',
                'title' => '9ct Gold Jewellery Banner',
                'data' => [
                    'image_id' => null,
                    'image_url' => 'assets/images/banner-image/homepage-newdesign24.jpg',
                    'badge' => '20% OFF',
                    'eyebrow' => null,
                    'heading' => '9CT GOLD JEWELLERY',
                    'subheading' => 'ETERNALLY BEAUTIFUL JEWELLERY',
                    'button_text' => 'Shop Now',
                    'button_url' => '#',
                    'theme' => 'dark',
                ],
            ],
            [
                'type' => 'feature_strip',
                'title' => 'Necklaces & Rings Strip',
                'data' => [
                    'heading' => null,
                    'theme' => 'light',
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/5.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/6.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/7.jpg',
                            'title' => 'RING',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'module_grid',
                'title' => 'Shop The Perfect Gift',
                'data' => [
                    'heading' => '<strong>SHOP</strong> THE PERFECT GIFT',
                    'subheading' => "Give a gift they'll cherish forever, we have something to suit every style & any occasion.",
                    'background' => 'grey',
                    'title_tag' => 'h4',
                    'image_rounded' => true,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/newdesign24.jpg',
                            'title' => '<strong>BIRTHDAY</strong> GIFT',
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'light',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/christmas24-childrens.jpg',
                            'title' => '<strong>BIRTHSTONE</strong> JEWELLERY',
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/newdesign25.jpg',
                            'title' => "<strong>OFFERS</strong> | DON'T MISS OUT",
                            'subtitle' => null,
                            'button_text' => 'Shop Now',
                            'url' => '#',
                            'text_style' => 'light',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'module_grid',
                'title' => 'Inspiration! Click To Shop',
                'data' => [
                    'heading' => '<strong>INSPIRATION!</strong> CLICK TO SHOP',
                    'subheading' => null,
                    'background' => 'white',
                    'title_tag' => 'h4',
                    'image_rounded' => true,
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/social-3.jpg',
                            'title' => null,
                            'subtitle' => null,
                            'button_text' => null,
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/christmas24-childrens.jpg',
                            'title' => null,
                            'subtitle' => null,
                            'button_text' => null,
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/man.jpg',
                            'title' => null,
                            'subtitle' => null,
                            'button_text' => null,
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/social-5.jpg',
                            'title' => null,
                            'subtitle' => null,
                            'button_text' => null,
                            'url' => '#',
                            'text_style' => 'dark',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'feature_strip',
                'title' => 'JG Magazine',
                'data' => [
                    'heading' => 'JG MAGAZINE',
                    'theme' => 'dark',
                    'items' => [
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/5.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/6.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/7.jpg',
                            'title' => 'RING',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                        [
                            'image_id' => null,
                            'image_url' => 'assets/images/product-image/4.jpg',
                            'title' => 'NECKLACES',
                            'subtitle' => null,
                            'url' => '#',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'social_follow',
                'title' => 'Follow Us On Social (static)',
                'data' => null,
            ],
            [
                'type' => 'text_columns',
                'title' => 'Pure and Preloved Jewellery (SEO Text)',
                'data' => [
                    'heading' => 'Pure and Preloved Jewellery',
                    'columns' => [
                        "At Pure and Preloved we have a passion for jewellery and we love bringing you the latest women's and men's jewellery from top UK jewellery brands and leading brands from around the world, such as Nomination, THOMAS SABO, Coeur De Lion, and Chlobo.",
                        'We can help you express your unique style and also switch it up depending on your mood. We have an amazing selection of jewellery from charms to wedding rings and a wide range of styles including unique gold and silver jewellery from our exclusive Pure and Preloved collections.',
                        "As engraving experts, we can personalise your favourite brands to create a truly memorable gift. We've engraved over 250,000 charms to create unique keepsakes! So why not browse online to find the perfect gift you've been searching for",
                    ],
                ],
            ],
        ];

        $now = now();
        $rows = [];

        foreach ($sections as $position => $section) {
            $rows[] = [
                'type' => $section['type'],
                'title' => $section['title'],
                'data' => $section['data'] === null ? null : json_encode($section['data']),
                'position' => $position,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('home_sections')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
