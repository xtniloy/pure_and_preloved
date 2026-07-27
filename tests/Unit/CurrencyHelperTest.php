<?php

namespace Tests\Unit;

use Tests\TestCase;

class CurrencyHelperTest extends TestCase
{
    public function test_default_symbol_and_formatting(): void
    {
        // config/currency.php defaults to GBP.
        $this->assertSame('£', currency_symbol());
        $this->assertSame('£1,250.50', currency(1250.5));
        $this->assertSame('£0.00', currency(0));
        $this->assertSame('£19.99', currency('19.99')); // string input is cast
        $this->assertSame('£1,000', currency(1000, 0));  // custom decimals
    }

    /**
     * currency_symbol() memoises per request, so switching currencies is
     * asserted in a fresh process where the static cache starts empty.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_symbol_switches_with_config(): void
    {
        config(['currency.code' => 'USD']);
        $this->assertSame('$', currency_symbol());
        $this->assertSame('$49.00', currency(49));
    }

    /**
     * An unmapped code falls back to the code itself as a prefix.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_unmapped_code_falls_back_to_the_code(): void
    {
        config(['currency.code' => 'AED']); // not in the symbols map
        $this->assertSame('AED ', currency_symbol());
    }
}
