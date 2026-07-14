<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_seeker_only_discovers_providers(): void
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        $provider = User::factory()->create(['role' => 'provider']);
        $otherSeeker = User::factory()->create(['role' => 'seeker']);

        $response = $this->actingAs($seeker)->getJson('/api/discover');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $provider->id)
            ->assertJsonPath('data.0.role', 'provider')
            ->assertJsonMissing(['id' => $otherSeeker->id])
            ->assertJsonMissing(['id' => $seeker->id]);
    }

    public function test_profiles_with_existing_swipes_are_omitted(): void
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        $availableProvider = User::factory()->create(['role' => 'provider']);
        $declinedProvider = User::factory()->create(['role' => 'provider']);
        $pendingProvider = User::factory()->create(['role' => 'provider']);

        UserMatch::create([
            'user_one_id' => $seeker->id,
            'user_two_id' => $declinedProvider->id,
            'status' => 'declined',
        ]);

        UserMatch::create([
            'user_one_id' => $pendingProvider->id,
            'user_two_id' => $seeker->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($seeker)->getJson('/api/discover');

        $response->assertOk()
            ->assertJsonFragment(['id' => $availableProvider->id])
            ->assertJsonMissing(['id' => $declinedProvider->id])
            ->assertJsonMissing(['id' => $pendingProvider->id]);
    }
}
