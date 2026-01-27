<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
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
    public function service_model_has_transactions_relation()
    {
        $service = Service::first();
        $this->assertNotNull($service->transactions());
    }

    /** @test */
    public function transaction_model_has_user_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $this->assertNotNull($transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
    }

    /** @test */
    public function transaction_model_has_service_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $this->assertNotNull($transaction->service);
        $this->assertEquals($service->id, $transaction->service->id);
    }

    /** @test */
    public function transaction_model_has_review_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        $transaction->refresh();
        $this->assertNotNull($transaction->review);
    }

    /** @test */
    public function user_address_model_has_user_relation()
    {
        $user = User::factory()->create();
        
        $address = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John',
            'phone' => '081234567890',
            'address' => 'Jl. Test',
            'is_default' => true,
        ]);

        $this->assertNotNull($address->user);
        $this->assertEquals($user->id, $address->user->id);
    }

    /** @test */
    public function review_model_has_user_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $review = Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        $this->assertNotNull($review->user);
        $this->assertEquals($user->id, $review->user->id);
    }

    /** @test */
    public function review_model_has_transaction_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $review = Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        $this->assertNotNull($review->transaction);
        $this->assertEquals($transaction->id, $review->transaction->id);
    }

    /** @test */
    public function user_model_has_addresses_relation()
    {
        $user = User::factory()->create();
        
        UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John',
            'phone' => '081234567890',
            'address' => 'Jl. Test',
            'is_default' => true,
        ]);

        $this->assertNotNull($user->addresses);
        $this->assertCount(1, $user->addresses);
    }

    /** @test */
    public function user_model_has_transactions_relation()
    {
        $user = User::factory()->create();
        $service = Service::first();
        
        Transaction::create([
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 1,
            'total_price' => 7000,
            'type' => 'dropoff',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        $this->assertNotNull($user->transactions);
        $this->assertCount(1, $user->transactions);
    }

    /** @test */
    public function user_model_has_default_address()
    {
        $user = User::factory()->create();
        
        UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John',
            'phone' => '081234567890',
            'address' => 'Jl. Test',
            'is_default' => true,
        ]);

        $this->assertNotNull($user->defaultAddress);
    }
}
