<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaundryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test service
        Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);
    }

    /** @test */
    public function guest_can_view_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('laundry.index');
    }

    /** @test */
    public function home_page_displays_services()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('services');
    }

    /** @test */
    public function guest_can_view_tracking_page()
    {
        $response = $this->get('/tracking');
        $response->assertStatus(200);
        $response->assertViewIs('tracking');
    }

    /** @test */
    public function tracking_page_shows_transaction_when_id_provided()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->get('/tracking?id=' . $transaction->id);
        $response->assertStatus(200);
        $response->assertViewHas('transaction');
    }

    /** @test */
    public function user_can_cancel_pending_order()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->post('/tracking/cancel/' . $transaction->id);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'cancelled',
            'payment_status' => '3',
        ]);
    }

    /** @test */
    public function user_cannot_cancel_processed_order()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'process', // Already processing
            'payment_status' => '2',
        ]);

        $response = $this->post('/tracking/cancel/' . $transaction->id);
        $response->assertSessionHas('error');
    }

    /** @test */
    public function invoice_requires_paid_status()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1', // Not paid yet
        ]);

        $response = $this->get('/tracking/print/' . $transaction->id);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function mark_as_paid_updates_payment_status()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->post('/tracking/paid/' . $transaction->id);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }

    /** @test */
    public function admin_redirected_from_home_to_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/');
        $response->assertRedirect(route('admin.index'));
    }
}
