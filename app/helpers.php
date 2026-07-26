<?php

if (! function_exists('currency_symbol')) {
    /**
     * Get the symbol for the currency configured in config/currency.php
     * (driven by the CURRENCY variable in .env). Falls back to the currency
     * code itself when no symbol is mapped.
     */
    function currency_symbol(): string
    {
        $code = config('currency.code', 'GBP');

        return config("currency.symbols.$code", $code . ' ');
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
