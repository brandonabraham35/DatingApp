<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\UserMatch;
use App\Models\User;
use App\Http\Resources\UserResource;

class MatchController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_two_id' => 'required|exists:users,id',
            'status' => 'required|in:accepted,declined',
        ]);

        $userOneId = $request->user()->id;
        $userTwoId = $validated['user_two_id'];
        $status = $validated['status'];

        if ((int)$userOneId === (int)$userTwoId) {
            throw ValidationException::withMessages([
                'user_two_id' => ['You cannot match with yourself.'],
            ]);
        }

        $existingMatch = UserMatch::where(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userOneId)->where('user_two_id', $userTwoId);
        })->orWhere(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userTwoId)->where('user_two_id', $userOneId);
        })->first();

        if ($existingMatch) {
            $isReciprocalAction = (int) $existingMatch->user_one_id === (int) $userTwoId;
            $isMutualMatch = $isReciprocalAction
                && $status === 'accepted'
                && in_array($existingMatch->status, ['pending', 'accepted'], true);

            if ($isReciprocalAction) {
                $existingMatch->update([
                    'status' => $status === 'declined' ? 'declined' : 'accepted',
                ]);

                return response()->json([
                    'match' => $existingMatch->fresh(),
                    'mutual_match' => $isMutualMatch,
                ]);
            }

            throw ValidationException::withMessages([
                'user_two_id' => ['A match with this user already exists.'],
            ]);
        }

        $match = UserMatch::create([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
            'status' => $status,
        ]);

        return response()->json([
            'match' => $match,
            'mutual_match' => false,
        ], 201);
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $matches = UserMatch::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->latest()
            ->get();

        $counterparts = User::query()
            ->whereIn('id', $matches->map(fn (UserMatch $match) =>
                (int) $match->user_one_id === (int) $userId ? $match->user_two_id : $match->user_one_id
            )->unique()->values())
            ->get()
            ->keyBy('id');

        // Keep the original array response and match attributes intact. `counterpart`
        // is an additive, safe profile projection for clients that need to restore a
        // connection list after a new session.
        $payload = $matches->map(function (UserMatch $match) use ($counterparts, $userId) {
            $counterpartId = (int) $match->user_one_id === (int) $userId
                ? $match->user_two_id
                : $match->user_one_id;

            return [
                ...$match->toArray(),
                'counterpart' => isset($counterparts[$counterpartId])
                    ? (new UserResource($counterparts[$counterpartId]))->resolve()
                    : null,
            ];
        });

        return response()->json($payload);
    }
}
