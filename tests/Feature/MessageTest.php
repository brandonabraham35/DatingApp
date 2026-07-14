<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserMatch;
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
}
