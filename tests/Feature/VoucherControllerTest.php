<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoucherControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);
    }

    /** @test */
    public function guest_can_access_vouchers_page()
    {
        $response = $this->get('/vouchers');
        $response->assertStatus(200);
    }

    /** @test */
    public function valid_voucher_returns_success()
    {
        $voucher = Voucher::create([
            'code' => 'VALID10',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $response = $this->post('/voucher/check', [
            'code' => 'VALID10',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => true]);
    }

    /** @test */
    public function invalid_voucher_code_returns_error()
    {
        $response = $this->post('/voucher/check', [
            'code' => 'NOTEXIST',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function expired_voucher_returns_error()
    {
        $voucher = Voucher::create([
            'code' => 'EXPIRED',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subMonth(),
            'valid_until' => now()->subDay(),
            'is_active' => true,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $response = $this->post('/voucher/check', [
            'code' => 'EXPIRED',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function voucher_below_minimum_order_returns_error()
    {
        $voucher = Voucher::create([
            'code' => 'MINORDER',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 100000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $response = $this->post('/voucher/check', [
            'code' => 'MINORDER',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function inactive_voucher_returns_error()
    {
        $voucher = Voucher::create([
            'code' => 'INACTIVE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => false,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $response = $this->post('/voucher/check', [
            'code' => 'INACTIVE',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function fixed_discount_voucher_works()
    {
        $voucher = Voucher::create([
            'code' => 'FIXED5K',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $response = $this->post('/voucher/check', [
            'code' => 'FIXED5K',
            'total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => true]);
    }
}
