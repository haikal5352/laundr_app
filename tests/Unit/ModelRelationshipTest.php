<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Review;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->service = Service::create([
            'name' => 'Cuci Komplit',
            'price' => 15000,
            'unit' => 'kg',
        ]);
    }

    /** @test */
    public function user_can_have_many_transactions()
    {
        Transaction::create([
            'customer_name' => 'Test 1',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        Transaction::create([
            'customer_name' => 'Test 2',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 5,
            'total_price' => 75000,
            'type' => 'pickup_delivery',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $this->assertCount(2, $this->user->transactions);
    }

    /** @test */
    public function user_can_have_many_addresses()
    {
        UserAddress::create([
            'user_id' => $this->user->id,
            'label' => 'Rumah',
            'recipient' => 'Test User',
            'address' => 'Jl. Test No. 1',
            'phone' => '081234567890',
            'is_default' => true,
        ]);

        UserAddress::create([
            'user_id' => $this->user->id,
            'label' => 'Kantor',
            'recipient' => 'Test User',
            'address' => 'Jl. Test No. 2',
            'phone' => '081234567891',
            'is_default' => false,
        ]);

        $this->assertCount(2, $this->user->addresses);
    }

    /** @test */
    public function user_has_default_address()
    {
        UserAddress::create([
            'user_id' => $this->user->id,
            'label' => 'Rumah',
            'recipient' => 'Test Default',
            'address' => 'Jl. Default',
            'phone' => '081234567890',
            'is_default' => true,
        ]);

        $this->assertNotNull($this->user->defaultAddress);
        $this->assertEquals('Rumah', $this->user->defaultAddress->label);
    }

    /** @test */
    public function user_is_admin_check_works()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function transaction_belongs_to_user()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($this->user->id, $transaction->user->id);
    }

    /** @test */
    public function transaction_belongs_to_service()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $this->assertInstanceOf(Service::class, $transaction->service);
        $this->assertEquals('Cuci Komplit', $transaction->service->name);
    }

    /** @test */
    public function transaction_can_have_review()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $this->user->id,
            'rating' => 5,
            'comment' => 'Pelayanan sangat bagus!',
        ]);

        $this->assertNotNull($transaction->review);
        $this->assertEquals(5, $transaction->review->rating);
    }

    /** @test */
    public function service_can_have_many_transactions()
    {
        Transaction::create([
            'customer_name' => 'Test 1',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        Transaction::create([
            'customer_name' => 'Test 2',
            'customer_phone' => '089876543210',
            'service_id' => $this->service->id,
            'user_id' => null,
            'qty' => 2,
            'total_price' => 30000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $this->assertCount(2, $this->service->transactions);
    }

    /** @test */
    public function review_belongs_to_user_and_transaction()
    {
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'qty' => 3,
            'total_price' => 45000,
            'type' => 'dropoff',
            'status' => 'done',
            'payment_status' => '2',
        ]);

        $review = Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $this->user->id,
            'rating' => 4,
            'comment' => 'Bagus!',
        ]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertInstanceOf(Transaction::class, $review->transaction);
    }
}
