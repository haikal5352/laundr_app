<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_addresses()
    {
        $response = $this->get('/profile/addresses');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_view_addresses_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/profile/addresses');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_add_new_address()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/profile/addresses', [
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
        ]);
    }

    /** @test */
    public function first_address_is_set_as_default()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)->post('/profile/addresses', [
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
        ]);

        $address = UserAddress::where('user_id', $user->id)->first();
        $this->assertTrue($address->is_default);
    }

    /** @test */
    public function user_can_update_address()
    {
        $user = User::factory()->create();
        $address = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Lama',
            'is_default' => true,
        ]);
        
        $response = $this->actingAs($user)->put('/profile/addresses/' . $address->id, [
            'label' => 'Kantor',
            'recipient' => 'Jane Doe',
            'phone' => '089876543210',
            'address' => 'Jl. Baru No. 456',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_addresses', [
            'id' => $address->id,
            'label' => 'Kantor',
            'recipient' => 'Jane Doe',
        ]);
    }

    /** @test */
    public function user_can_delete_address()
    {
        $user = User::factory()->create();
        $address = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Test',
            'is_default' => true,
        ]);
        
        $response = $this->actingAs($user)->delete('/profile/addresses/' . $address->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id,
        ]);
    }

    /** @test */
    public function user_can_set_default_address()
    {
        $user = User::factory()->create();
        
        $address1 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Pertama',
            'is_default' => true,
        ]);
        
        $address2 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Kantor',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Kedua',
            'is_default' => false,
        ]);
        
        $response = $this->actingAs($user)->post('/profile/addresses/' . $address2->id . '/default');

        $response->assertRedirect();
        
        $address1->refresh();
        $address2->refresh();
        
        $this->assertFalse($address1->is_default);
        $this->assertTrue($address2->is_default);
    }

    /** @test */
    public function deleting_default_address_sets_another_as_default()
    {
        $user = User::factory()->create();
        
        $address1 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Pertama',
            'is_default' => true,
        ]);
        
        $address2 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Kantor',
            'recipient' => 'John Doe',
            'phone' => '081234567890',
            'address' => 'Jl. Kedua',
            'is_default' => false,
        ]);
        
        $this->actingAs($user)->delete('/profile/addresses/' . $address1->id);

        $address2->refresh();
        $this->assertTrue($address2->is_default);
    }
}
