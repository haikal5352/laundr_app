<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Voucher;
use App\Models\Transaction;
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
    public function user_can_view_voucher_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/vouchers');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_validate_valid_voucher()
    {
        $user = User::factory()->create();
        
        Voucher::create([
            'code' => 'VALID10',
            'description' => 'Diskon 10%',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order' => 10000,
            'max_uses' => 100,
            'uses_count' => 0,
            'is_active' => true,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addWeek(),
        ]);
        
        $response = $this->actingAs($user)->post('/vouchers/validate', [
            'code' => 'VALID10',
            'order_total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => true]);
    }

    /** @test */
    public function invalid_voucher_code_returns_error()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/vouchers/validate', [
            'code' => 'NOTEXIST',
            'order_total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function expired_voucher_returns_error()
    {
        $user = User::factory()->create();
        
        Voucher::create([
            'code' => 'EXPIRED',
            'description' => 'Expired voucher',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order' => 10000,
            'max_uses' => 100,
            'uses_count' => 0,
            'is_active' => true,
            'valid_from' => now()->subMonth(),
            'valid_until' => now()->subWeek(),
        ]);
        
        $response = $this->actingAs($user)->post('/vouchers/validate', [
            'code' => 'EXPIRED',
            'order_total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function voucher_with_min_order_not_met_returns_error()
    {
        $user = User::factory()->create();
        
        Voucher::create([
            'code' => 'MINORDER',
            'description' => 'Min order 50k',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order' => 50000,
            'max_uses' => 100,
            'uses_count' => 0,
            'is_active' => true,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addWeek(),
        ]);
        
        $response = $this->actingAs($user)->post('/vouchers/validate', [
            'code' => 'MINORDER',
            'order_total' => 20000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    /** @test */
    public function inactive_voucher_returns_error()
    {
        $user = User::factory()->create();
        
        Voucher::create([
            'code' => 'INACTIVE',
            'description' => 'Inactive voucher',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order' => 10000,
            'max_uses' => 100,
            'uses_count' => 0,
            'is_active' => false,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addWeek(),
        ]);
        
        $response = $this->actingAs($user)->post('/vouchers/validate', [
            'code' => 'INACTIVE',
            'order_total' => 50000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }
}
