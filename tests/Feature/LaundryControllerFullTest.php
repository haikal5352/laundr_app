<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaundryControllerFullTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->service = Service::create([
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
    }

    /** @test */
    public function user_can_view_home_page()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_is_redirected_from_home()
    {
        $response = $this->actingAs($this->admin)->get('/');
        $response->assertRedirect(route('admin.index'));
    }

    /** @test */
    public function user_can_store_order_with_cash_payment()
    {
        $items = [
            ['service_id' => $this->service->id, 'qty' => 3]
        ];

        $response = $this->actingAs($this->user)->post('/store', [
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'type' => 'dropoff',
            'address' => '',
            'payment_method' => 'cash',
            'items_json' => json_encode($items),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Test Customer',
            'payment_method' => 'cash',
        ]);
    }

    /** @test */
    public function store_fails_without_items()
    {
        $response = $this->actingAs($this->user)->post('/store', [
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'type' => 'dropoff',
            'items_json' => json_encode([]),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function store_validates_required_fields()
    {
        $items = [['service_id' => $this->service->id, 'qty' => 2]];

        $response = $this->actingAs($this->user)->post('/store', [
            'items_json' => json_encode($items),
        ]);

        $response->assertSessionHasErrors(['customer_name', 'customer_phone', 'type']);
    }

    /** @test */
    public function store_requires_address_for_pickup_delivery()
    {
        $items = [['service_id' => $this->service->id, 'qty' => 2]];

        $response = $this->actingAs($this->user)->post('/store', [
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'type' => 'pickup_delivery',
            'address' => '',
            'items_json' => json_encode($items),
        ]);

        $response->assertSessionHasErrors(['address']);
    }

    /** @test */
    public function user_can_store_order_with_voucher()
    {
        $voucher = Voucher::create([
            'code' => 'DISC10',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order' => 10000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
            'quota' => 100,
            'used_count' => 0,
        ]);

        $items = [['service_id' => $this->service->id, 'qty' => 5]];

        $response = $this->actingAs($this->user)->post('/store', [
            'customer_name' => 'Voucher Test',
            'customer_phone' => '081234567890',
            'type' => 'dropoff',
            'payment_method' => 'cash',
            'voucher_code' => 'DISC10',
            'items_json' => json_encode($items),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Voucher Test',
            'voucher_code' => 'DISC10',
        ]);
    }

    /** @test */
    public function user_can_view_tracking_page()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($this->user)->get('/tracking?id=' . $transaction->id);
        $response->assertStatus(200);
    }

    /** @test */
    public function tracking_page_without_id_shows_empty()
    {
        $response = $this->actingAs($this->user)->get('/tracking');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_cancel_pending_order()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Cancel Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($this->user)->post('/cancel/' . $transaction->id);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'cancelled',
        ]);
    }

    /** @test */
    public function cannot_cancel_non_pending_order()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Done Order',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($this->user)->post('/cancel/' . $transaction->id);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function user_can_mark_order_as_paid()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Pay Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($this->user)->post('/mark-paid/' . $transaction->id);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'payment_status' => '2',
        ]);
    }

    /** @test */
    public function mark_paid_does_nothing_if_already_paid()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Already Paid',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($this->user)->post('/mark-paid/' . $transaction->id);
        $response->assertRedirect();
    }

    /** @test */
    public function user_can_download_invoice_if_paid()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Invoice Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($this->user)->get('/invoice/' . $transaction->id);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function cannot_download_invoice_if_not_paid()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Unpaid Invoice',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $response = $this->actingAs($this->user)->get('/invoice/' . $transaction->id);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function midtrans_callback_updates_payment_status_on_settlement()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Callback Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $orderId = 'TRX-' . $transaction->id . '-' . time();
        $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY', 'test');
        $signatureKey = hash('sha512', $orderId . '200' . '14000.00' . $serverKey);

        $response = $this->post('/callback', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '14000.00',
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
        ]);

        $transaction->refresh();
        $this->assertEquals('2', $transaction->payment_status);
    }

    /** @test */
    public function midtrans_callback_cancels_on_expire()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Expire Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $orderId = 'TRX-' . $transaction->id . '-' . time();
        $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY', 'test');
        $signatureKey = hash('sha512', $orderId . '200' . '14000.00' . $serverKey);

        $response = $this->post('/callback', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '14000.00',
            'signature_key' => $signatureKey,
            'transaction_status' => 'expire',
        ]);

        $transaction->refresh();
        $this->assertEquals('3', $transaction->payment_status);
        $this->assertEquals('cancelled', $transaction->status);
    }

    /** @test */
    public function callback_ignores_cancelled_orders()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Cancelled Order',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 2,
            'total_price' => 14000,
            'type' => 'dropoff',
            'status' => 'cancelled',
            'payment_status' => '3',
        ]);

        $orderId = 'TRX-' . $transaction->id . '-' . time();
        $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY', 'test');
        $signatureKey = hash('sha512', $orderId . '200' . '14000.00' . $serverKey);

        $response = $this->post('/callback', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '14000.00',
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function store_with_multiple_items()
    {
        $service2 = Service::create([
            'name' => 'Setrika',
            'price' => 5000,
            'unit' => 'kg',
        ]);

        $items = [
            ['service_id' => $this->service->id, 'qty' => 2],
            ['service_id' => $service2->id, 'qty' => 3],
        ];

        $response = $this->actingAs($this->user)->post('/store', [
            'customer_name' => 'Multi Item',
            'customer_phone' => '081234567890',
            'type' => 'dropoff',
            'payment_method' => 'cash',
            'items_json' => json_encode($items),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Multi Item',
            'qty' => 5, // 2 + 3
        ]);
    }
}
