<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Reciprocal role filtering.
        $targetRole = $user->role === 'seeker' ? 'provider' : 'seeker';

        $profiles = User::query()
            ->where('role', $targetRole)
            ->where('id', '!=', $user->id)
            ->whereNotExists(function ($query) use ($user) {
                // Exclude a candidate only when that candidate is part of a match with this user.
                $query->select(DB::raw(1))
                    ->from('matches')
                    ->whereIn('status', ['pending', 'accepted', 'declined'])
                    ->where(function ($matchQuery) use ($user) {
                        $matchQuery
                            ->where(function ($direction) use ($user) {
                                $direction->where('user_one_id', $user->id)
                                    ->whereColumn('user_two_id', 'users.id');
                            })
                            ->orWhere(function ($direction) use ($user) {
                                $direction->where('user_two_id', $user->id)
                                    ->whereColumn('user_one_id', 'users.id');
                            });
                    });
            })
            ->orderByDesc('is_verified')
            ->when($user->location, function ($query, $location) {
                $query->orderByRaw('CASE WHEN location = ? THEN 1 ELSE 0 END DESC', [$location]);
            })
            ->paginate(15);

        return UserResource::collection($profiles);
    }
}
