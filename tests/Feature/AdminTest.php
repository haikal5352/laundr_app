<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->service = Service::create([
            'name' => 'Cuci Setrika',
            'price' => 10000,
            'unit' => 'kg',
        ]);
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertViewIs('admin.index');
    }

    /** @test */
    public function admin_dashboard_shows_statistics()
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertViewHas('totalTransactions');
        $response->assertViewHas('income');
        $response->assertViewHas('pendingOrders');
        $response->assertViewHas('totalCustomers');
    }

    /** @test */
    public function non_admin_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_transaction_status()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'qty' => 5,
            'total_price' => 50000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch('/admin/transaction/' . $transaction->id, [
                'status' => 'process'
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'process',
        ]);
    }

    /** @test */
    public function admin_can_update_payment_status()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'qty' => 5,
            'total_price' => 50000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch('/admin/transaction/' . $transaction->id . '/payment', [
                'payment_status' => '2'
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }

    /** @test */
    public function admin_can_export_excel()
    {
        Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'qty' => 5,
            'total_price' => 50000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2', // Lunas
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
    }

    /** @test */
    public function admin_can_view_vouchers_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/vouchers');
        $response->assertStatus(200);
        $response->assertViewIs('admin.vouchers');
    }

    /** @test */
    public function admin_can_create_voucher()
    {
        $response = $this->actingAs($this->admin)->post('/admin/vouchers', [
            'code' => 'TESTVOUCHER',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('vouchers', [
            'code' => 'TESTVOUCHER',
        ]);
    }

    /** @test */
    public function admin_can_delete_voucher()
    {
        $voucher = \App\Models\Voucher::create([
            'code' => 'DELETEME',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/vouchers/' . $voucher->id);

        $this->assertDatabaseMissing('vouchers', [
            'id' => $voucher->id,
        ]);
    }
}
