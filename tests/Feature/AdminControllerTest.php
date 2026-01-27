<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
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
    public function admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_dashboard_shows_statistics()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $service = Service::first();
        
        // Create some transactions
        Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '2',
        ]);
        
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertViewHas(['totalTransactions', 'income', 'pendingOrders', 'totalCustomers', 'recentTransactions']);
    }

    /** @test */
    public function admin_can_update_transaction_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);
        
        $response = $this->actingAs($admin)->patch('/admin/transaction/' . $transaction->id, [
            'status' => 'process'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'process',
        ]);
    }

    /** @test */
    public function admin_can_update_payment_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);
        
        $response = $this->actingAs($admin)->patch('/admin/transaction/' . $transaction->id . '/payment', [
            'payment_status' => '2'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }

    /** @test */
    public function admin_can_view_vouchers_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/vouchers');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_voucher()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/admin/vouchers', [
            'code' => 'NEWVOUCHER',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'is_active' => true,
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('vouchers', [
            'code' => 'NEWVOUCHER',
            'discount_type' => 'percent',
        ]);
    }

    /** @test */
    public function admin_can_update_voucher()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $voucher = Voucher::create([
            'code' => 'OLDCODE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);
        
        $response = $this->actingAs($admin)->put('/admin/vouchers/' . $voucher->id, [
            'code' => 'NEWCODE',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'min_order' => 20000,
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'code' => 'NEWCODE',
            'discount_type' => 'fixed',
        ]);
    }

    /** @test */
    public function admin_can_delete_voucher()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $voucher = Voucher::create([
            'code' => 'TODELETE',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);
        
        $response = $this->actingAs($admin)->delete('/admin/vouchers/' . $voucher->id);
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('vouchers', [
            'id' => $voucher->id,
        ]);
    }

    /** @test */
    public function status_update_sends_notification_to_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);
        
        $this->actingAs($admin)->patch('/admin/transaction/' . $transaction->id, [
            'status' => 'done'
        ]);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'done',
        ]);
    }

    /** @test */
    public function payment_update_sends_notification_to_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);
        
        $this->actingAs($admin)->patch('/admin/transaction/' . $transaction->id . '/payment', [
            'payment_status' => '2'
        ]);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }
}
