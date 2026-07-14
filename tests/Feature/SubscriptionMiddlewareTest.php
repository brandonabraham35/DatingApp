<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserMatch;
use App\Http\Middleware\EnsureUserIsSubscribed;

class SubscriptionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocks_unpaid_seekers()
    {
        $seeker = User::factory()->create(['role' => 'seeker']);

        $request = Request::create('/api/messages', 'POST');
        $request->setUserResolver(function () use ($seeker) {
            return $seeker;
        });
        $request->headers->set('Accept', 'application/json');

        $middleware = new EnsureUserIsSubscribed();

        $response = $middleware->handle($request, function () {
            return response()->json(['message' => 'Passed']);
        });

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Payment Required.', json_decode($response->getContent(), true)['message']);
    }

    public function test_allows_active_subscribers()
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        // Fake subscription using Cashier methods
        $seeker->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_123',
            'stripe_status' => 'active',
            'stripe_price' => 'price_123',
            'quantity' => 1,
        ]);

        $request = Request::create('/api/messages', 'POST');
        $request->setUserResolver(function () use ($seeker) {
            return $seeker;
        });

        $middleware = new EnsureUserIsSubscribed();

        $response = $middleware->handle($request, function () {
            return response()->json(['message' => 'Passed']);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Passed', json_decode($response->getContent(), true)['message']);
    }

    public function test_allows_providers_to_pass()
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $request = Request::create('/api/messages', 'POST');
        $request->setUserResolver(function () use ($provider) {
            return $provider;
        });

        $middleware = new EnsureUserIsSubscribed();

        $response = $middleware->handle($request, function () {
            return response()->json(['message' => 'Passed']);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Passed', json_decode($response->getContent(), true)['message']);
    }
}
