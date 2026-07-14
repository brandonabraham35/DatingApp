<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserMatch;

class DiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_discover_returns_only_uninteracted_opposite_role()
    {
        $seeker = User::factory()->create(['role' => 'seeker']);

        $provider1 = User::factory()->create(['role' => 'provider']); // Uninteracted
        $provider2 = User::factory()->create(['role' => 'provider']); // Interacted
        $anotherSeeker = User::factory()->create(['role' => 'seeker']);

        // Create an interaction with provider2
        UserMatch::create([
            'user_one_id' => $seeker->id,
            'user_two_id' => $provider2->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($seeker)->getJson('/api/discover');

        $response->assertStatus(200);

        $data = $response->json('data');

        // Should return only 1 profile (provider1)
        $this->assertCount(1, $data);
        $this->assertEquals($provider1->id, $data[0]['id']);

        // Assert another seeker is not returned
        $response->assertJsonMissing(['id' => $anotherSeeker->id]);

        // Assert the interacted provider is not returned
        $response->assertJsonMissing(['id' => $provider2->id]);
    }
}
