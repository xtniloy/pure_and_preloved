<?php

if (! function_exists('currency_symbol')) {
    /**
     * Get the symbol for the currency configured in config/currency.php
     * (driven by the CURRENCY variable in .env). Falls back to the currency
     * code itself when no symbol is mapped.
     */
    function currency_symbol(): string
    {
        // Resolved once per request — the currency can't change mid-request,
        // so we avoid repeating config() lookups for every price on the page.
        static $symbol = null;

        if ($symbol === null) {
            $code = config('currency.code', 'GBP');
            $symbol = config("currency.symbols.$code", $code . ' ');
        }

        return $symbol;
    }
}

if (! function_exists('currency')) {
    /**
     * Format an amount with the active currency symbol, e.g. "£1,250.00".
     */
    function currency($amount, int $decimals = 2): string
    {
        return currency_symbol() . number_format((float) $amount, $decimals);
    }
}

if (! function_exists('seo_robots')) {
    /**
     * Robots meta content for the current request, driven by SEO_INDEXING
     * in .env (see config/seo.php). Resolved once per request.
     */
    function seo_robots(): string
    {
        static $robots = null;

        if ($robots === null) {
            $robots = config('seo.indexing', false) ? 'index, follow' : 'noindex, nofollow';
        }

        return $robots;
    }
}
