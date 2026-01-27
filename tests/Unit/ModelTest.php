<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // === SERVICE MODEL TESTS ===
    
    /** @test */
    public function service_has_many_transactions()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
        Transaction::create([
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

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $service->transactions);
        $this->assertCount(1, $service->transactions);
    }

    // === TRANSACTION MODEL TESTS ===

    /** @test */
    public function transaction_belongs_to_user()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
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

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
    }

    /** @test */
    public function transaction_belongs_to_service()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
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

        $this->assertInstanceOf(Service::class, $transaction->service);
        $this->assertEquals($service->id, $transaction->service->id);
    }

    /** @test */
    public function transaction_has_one_review()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
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

        $this->assertNotNull($transaction->review);
        $this->assertEquals(5, $transaction->review->rating);
    }

    // === REVIEW MODEL TESTS ===

    /** @test */
    public function review_belongs_to_user()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $review = Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Excellent!',
        ]);

        $this->assertInstanceOf(User::class, $review->user);
    }

    /** @test */
    public function review_belongs_to_transaction()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $review = Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Good!',
        ]);

        $this->assertInstanceOf(Transaction::class, $review->transaction);
    }

    // === USER ADDRESS MODEL TESTS ===

    /** @test */
    public function user_address_belongs_to_user()
    {
        $user = User::factory()->create();
        
        $address = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'is_default' => true,
        ]);

        $this->assertInstanceOf(User::class, $address->user);
        $this->assertEquals($user->id, $address->user->id);
    }

    // === USER MODEL TESTS ===

    /** @test */
    public function user_has_many_addresses()
    {
        $user = User::factory()->create();
        
        UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'is_default' => true,
        ]);

        UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Kantor',
            'recipient' => 'John Doe',
            'phone' => '081234567891',
            'address' => 'Jl. Kantor No. 456',
            'is_default' => false,
        ]);

        $this->assertCount(2, $user->addresses);
    }

    /** @test */
    public function user_has_many_transactions()
    {
        $service = Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'unit' => 'kg',
            'description' => 'Layanan cuci kering'
        ]);

        $user = User::factory()->create();
        
        Transaction::create([
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

        $this->assertCount(1, $user->transactions);
    }
}
