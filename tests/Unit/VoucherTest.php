<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function voucher_is_invalid_when_not_active()
    {
        $voucher = Voucher::create([
            'code' => 'INACTIVE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => false,
        ]);

        $result = $voucher->isValid(100000);
        
        $this->assertFalse($result['valid']);
        $this->assertEquals('Voucher tidak aktif', $result['message']);
    }

    /** @test */
    public function voucher_is_invalid_when_expired()
    {
        $voucher = Voucher::create([
            'code' => 'EXPIRED',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now()->subMonth(),
            'valid_until' => now()->subDay(),
            'is_active' => true,
        ]);

        $result = $voucher->isValid(100000);
        
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('expired', $result['message']);
    }

    /** @test */
    public function voucher_is_invalid_when_not_yet_valid()
    {
        $voucher = Voucher::create([
            'code' => 'FUTURE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now()->addDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $result = $voucher->isValid(100000);
        
        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function voucher_is_invalid_when_quota_exhausted()
    {
        $voucher = Voucher::create([
            'code' => 'QUOTAFULL',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'quota' => 5,
            'used_count' => 5, // Already used all quota
        ]);

        $result = $voucher->isValid(100000);
        
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('habis', $result['message']);
    }

    /** @test */
    public function voucher_is_invalid_when_minimum_order_not_met()
    {
        $voucher = Voucher::create([
            'code' => 'MINORDER',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'min_order' => 50000,
        ]);

        $result = $voucher->isValid(30000); // Below minimum
        
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Minimum order', $result['message']);
    }

    /** @test */
    public function voucher_is_valid_when_all_conditions_met()
    {
        $voucher = Voucher::create([
            'code' => 'VALIDCODE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'min_order' => 20000,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $result = $voucher->isValid(50000);
        
        $this->assertTrue($result['valid']);
        $this->assertEquals('Voucher valid', $result['message']);
    }

    /** @test */
    public function percent_discount_calculated_correctly()
    {
        $voucher = Voucher::create([
            'code' => 'PERCENT20',
            'discount_type' => 'percent',
            'discount_value' => 20,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $discount = $voucher->calculateDiscount(100000);
        
        $this->assertEquals(20000, $discount); // 20% of 100000
    }

    /** @test */
    public function percent_discount_capped_by_max_discount()
    {
        $voucher = Voucher::create([
            'code' => 'MAXCAP',
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_discount' => 10000, // Cap at 10000
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $discount = $voucher->calculateDiscount(100000); // 50% would be 50000
        
        $this->assertEquals(10000, $discount); // Capped at max_discount
    }

    /** @test */
    public function fixed_discount_calculated_correctly()
    {
        $voucher = Voucher::create([
            'code' => 'FIXED5K',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $discount = $voucher->calculateDiscount(100000);
        
        $this->assertEquals(5000, $discount);
    }

    /** @test */
    public function discount_cannot_exceed_order_total()
    {
        $voucher = Voucher::create([
            'code' => 'BIGDISCOUNT',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $discount = $voucher->calculateDiscount(30000); // Order is only 30000
        
        $this->assertEquals(30000, $discount); // Can't exceed order total
    }
}
