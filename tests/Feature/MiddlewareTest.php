<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Service::create([
            'name' => 'Test Service',
            'price' => 10000,
            'unit' => 'kg',
        ]);
    }

    /** @test */
    public function admin_middleware_allows_admin_access()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_middleware_blocks_non_admin()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function admin_middleware_redirects_guest()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function is_admin_middleware_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function is_admin_middleware_blocks_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect();
    }

    /** @test */
    public function auth_middleware_protects_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function auth_middleware_protects_addresses()
    {
        $response = $this->get('/addresses');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_addresses()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/addresses');
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_middleware_redirects_authenticated_user_from_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function guest_middleware_redirects_authenticated_user_from_register()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/dashboard');
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
            'user_id' => null,
            'qty' => 2,
            'total_price' => 20000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($admin)->post('/admin/update-status/' . $transaction->id, [
            'status' => 'processing',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'processing',
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
            'user_id' => null,
            'qty' => 2,
            'total_price' => 20000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($admin)->post('/admin/update-payment/' . $transaction->id, [
            'payment_status' => '2',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }
}
