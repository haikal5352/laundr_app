<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_mark_notifications_as_read()
    {
        $response = $this->post('/notifications/mark-all-read');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        
        // Create a fake notification
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\StatusNotification',
            'data' => ['title' => 'Test', 'message' => 'Test notification'],
            'read_at' => null,
        ]);
        
        $response = $this->actingAs($user)->post('/notifications/mark-all-read');
        $response->assertRedirect();
        
        // Check notification is now read
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    /** @test */
    public function user_can_mark_single_notification_as_read()
    {
        $user = User::factory()->create();
        
        // Create a fake notification
        $notificationId = \Illuminate\Support\Str::uuid()->toString();
        $user->notifications()->create([
            'id' => $notificationId,
            'type' => 'App\Notifications\StatusNotification',
            'data' => ['title' => 'Test', 'message' => 'Test notification'],
            'read_at' => null,
        ]);
        
        $response = $this->actingAs($user)->post('/notifications/' . $notificationId . '/read');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
