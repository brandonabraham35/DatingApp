<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMatch;
use App\Http\Resources\UserResource;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $oppositeRole = $user->role === 'seeker' ? 'provider' : 'seeker';

        // Get IDs of users the current user has already interacted with (matched, liked, or ignored)
        $interactedUserIds = UserMatch::where('user_one_id', $user->id)
            ->pluck('user_two_id')
            ->toArray();

        // Also check if they are user_two_id to be safe
        $interactedUserIds2 = UserMatch::where('user_two_id', $user->id)
            ->pluck('user_one_id')
            ->toArray();

        $allExcludedIds = array_merge($interactedUserIds, $interactedUserIds2, [$user->id]);

        $query = User::where('role', $oppositeRole)
            ->whereNotIn('id', $allExcludedIds);

        // Optional Geographic filtering
        if ($request->has('location')) {
            $query->locatedIn($request->location);
        }

        // Order dynamically (e.g., showing verified users first)
        $query->orderBy('is_verified', 'desc')
              ->orderBy('created_at', 'desc');

        // Paginate results
        $profiles = $query->paginate(15);

        // For API responses, we can map to our safe UserResource and append dynamic attributes
        // UserResource needs to be adjusted to output 'age' if we want it there, or we can just append it here
        $profiles->getCollection()->transform(function ($profile) {
            $profile->age = $profile->age; // Load the attribute
            return new UserResource($profile);
        });

        return response()->json($profiles);
    }
}
