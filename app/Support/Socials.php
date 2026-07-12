<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Socials
{
    /**
     * Platforms offered in the admin Social Links editor, mapped to the
     * theme's ionicons classes (used in the footer, off-canvas menu and
     * the homepage social strip).
     */
    public const PLATFORMS = [
        'facebook' => ['label' => 'Facebook', 'icon' => 'ion-social-facebook'],
        'twitter' => ['label' => 'X (Twitter)', 'icon' => 'ion-social-twitter'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'ion-social-instagram'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'ion-social-youtube'],
        'pinterest' => ['label' => 'Pinterest', 'icon' => 'ion-social-pinterest'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'ion-social-linkedin'],
        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'ion-social-whatsapp'],
        'snapchat' => ['label' => 'Snapchat', 'icon' => 'ion-social-snapchat'],
        'tumblr' => ['label' => 'Tumblr', 'icon' => 'ion-social-tumblr'],
        'vimeo' => ['label' => 'Vimeo', 'icon' => 'ion-social-vimeo'],
        'skype' => ['label' => 'Skype', 'icon' => 'ion-social-skype'],
        'reddit' => ['label' => 'Reddit', 'icon' => 'ion-social-reddit'],
        'google' => ['label' => 'Google / Threads', 'icon' => 'ion-social-google'],
    ];

    public const CACHE_KEY = 'socials:links';

    /**
     * The configured social links, in display order, with the icon class
     * attached. Memoized per request AND cached persistently (the footer,
     * off-canvas and homepage strip all call this on every page); the admin
     * Social Links editor busts the cache via clearCache().
     */
    public static function links(): array
    {
        static $links = null;

        if ($links !== null) {
            return $links;
        }

        return $links = Cache::remember(self::CACHE_KEY, now()->addHours(6), function () {
            $links = [];

            foreach (Setting::getJson('social_links', []) as $row) {
                $platform = $row['platform'] ?? null;
                $url = trim((string) ($row['url'] ?? ''));

                if ($url === '' || !isset(self::PLATFORMS[$platform])) {
                    continue;
                }

                $links[] = [
                    'platform' => $platform,
                    'url' => $url,
                    'icon' => self::PLATFORMS[$platform]['icon'],
                    'label' => self::PLATFORMS[$platform]['label'],
                ];
            }

            return $links;
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
