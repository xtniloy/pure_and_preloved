<?php

namespace App\Support;

use App\Models\Setting;

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

    /**
     * The configured social links, in display order, with the icon class
     * attached. Cached for the duration of the request (the footer,
     * off-canvas and homepage strip all call this on the same page).
     */
    public static function links(): array
    {
        static $links = null;

        if ($links !== null) {
            return $links;
        }

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
    }
}
