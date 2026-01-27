<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewControllerTest extends TestCase
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
    public function guest_can_view_reviews_page()
    {
        $response = $this->get('/reviews');
        $response->assertStatus(200);
        $response->assertViewIs('reviews.index');
    }

    /** @test */
    public function reviews_page_shows_reviews()
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
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Excellent service!',
        ]);

        $response = $this->get('/reviews');
        $response->assertStatus(200);
        $response->assertViewHas('reviews');
    }

    /** @test */
    public function user_can_submit_review()
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
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($user)->post('/reviews', [
            'transaction_id' => $transaction->id,
            'rating' => 5,
            'comment' => 'Great laundry service!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_cannot_submit_duplicate_review()
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
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        // First review
        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Good!',
        ]);

        // Try to submit another review
        $response = $this->actingAs($user)->post('/reviews', [
            'transaction_id' => $transaction->id,
            'rating' => 5,
            'comment' => 'Duplicate!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function user_cannot_review_other_users_transaction()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $service = Service::first();
        
        $transaction = Transaction::create([
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'service_id' => $service->id,
            'user_id' => $user1->id,
            'qty' => 3,
            'total_price' => 21000,
            'type' => 'dropoff',
            'status' => 'taken',
            'payment_status' => '2',
        ]);

        $response = $this->actingAs($user2)->post('/reviews', [
            'transaction_id' => $transaction->id,
            'rating' => 5,
            'comment' => 'Not mine!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
