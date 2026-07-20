<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\Message;
use App\Events\MessageSent;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_and_event_is_dispatched()
    {
        Event::fake();

        $sender = User::factory()->create(['role' => 'seeker']);
        // Need to give sender a subscription to bypass EnsureUserIsSubscribed middleware
        $sender->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_123',
            'stripe_status' => 'active',
            'stripe_price' => 'price_123',
            'quantity' => 1,
        ]);

        $receiver = User::factory()->create();

        UserMatch::create([
            'user_one_id' => $sender->id,
            'user_two_id' => $receiver->id,
            'status' => 'accepted'
        ]);

        $response = $this->actingAs($sender)->postJson('/api/messages', [
            'receiver_id' => $receiver->id,
            'message' => 'Hello there!'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => 'Hello there!'
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($sender, $receiver) {
            return $event->message->sender_id === $sender->id &&
                   $event->message->receiver_id === $receiver->id &&
                   $event->message->message === 'Hello there!';
        });
    }

    public function test_message_index_supports_an_opt_in_descending_page_without_changing_default_order(): void
    {
        $sender = User::factory()->create(['role' => 'seeker']);
        $receiver = User::factory()->create(['role' => 'provider']);
        UserMatch::create([
            'user_one_id' => $sender->id,
            'user_two_id' => $receiver->id,
            'status' => 'accepted',
        ]);

        $older = Message::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'message' => 'Older']);
        $newer = Message::create(['sender_id' => $receiver->id, 'receiver_id' => $sender->id, 'message' => 'Newer']);
        $older->forceFill(['created_at' => now()->subMinute()])->save();
        $newer->forceFill(['created_at' => now()])->save();

        $this->actingAs($sender, 'sanctum')->getJson("/api/messages/{$receiver->id}")
            ->assertOk()
            ->assertJsonPath('data.0.message', 'Older');

        $this->actingAs($sender, 'sanctum')->getJson("/api/messages/{$receiver->id}?order=desc")
            ->assertOk()
            ->assertJsonPath('data.0.message', 'Newer');
    }
}
