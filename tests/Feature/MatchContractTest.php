<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_swipe_persists_the_requested_status(): void
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($seeker, 'sanctum')->postJson('/api/matches', [
            'user_two_id' => $provider->id,
            'status' => 'accepted',
        ]);

        $response->assertCreated()
            ->assertJsonPath('mutual_match', false)
            ->assertJsonPath('match.status', 'accepted');

        $this->assertDatabaseHas('matches', [
            'user_one_id' => $seeker->id,
            'user_two_id' => $provider->id,
            'status' => 'accepted',
        ]);
    }

    public function test_a_reciprocal_accepted_swipe_reports_a_mutual_match(): void
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        $provider = User::factory()->create(['role' => 'provider']);

        $this->actingAs($seeker, 'sanctum')->postJson('/api/matches', [
            'user_two_id' => $provider->id,
            'status' => 'accepted',
        ])->assertCreated();

        $response = $this->actingAs($provider, 'sanctum')->postJson('/api/matches', [
            'user_two_id' => $seeker->id,
            'status' => 'accepted',
        ]);

        $response->assertOk()
            ->assertJsonPath('mutual_match', true)
            ->assertJsonPath('match.status', 'accepted');
    }

    public function test_matches_index_adds_a_safe_counterpart_profile_without_removing_match_fields(): void
    {
        $seeker = User::factory()->create(['role' => 'seeker']);
        $provider = User::factory()->create(['role' => 'provider']);

        $match = \App\Models\UserMatch::create([
            'user_one_id' => $seeker->id,
            'user_two_id' => $provider->id,
            'status' => 'accepted',
        ]);

        $this->actingAs($seeker, 'sanctum')->getJson('/api/matches')
            ->assertOk()
            ->assertJsonPath('0.id', $match->id)
            ->assertJsonPath('0.status', 'accepted')
            ->assertJsonPath('0.counterpart.id', $provider->id)
            ->assertJsonMissingPath('0.counterpart.email');
    }
}
