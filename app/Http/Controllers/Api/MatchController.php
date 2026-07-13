<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\UserMatch;

class MatchController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_two_id' => 'required|exists:users,id',
        ]);

        $userOneId = $request->user()->id;
        $userTwoId = $validated['user_two_id'];

        if ((int)$userOneId === (int)$userTwoId) {
            throw ValidationException::withMessages([
                'user_two_id' => ['You cannot match with yourself.'],
            ]);
        }

        // Prevent duplicate matching (A matching B when A already matched B, or B already matched A)
        $existingMatch = UserMatch::where(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userOneId)->where('user_two_id', $userTwoId);
        })->orWhere(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userTwoId)->where('user_two_id', $userOneId);
        })->first();

        if ($existingMatch) {
            throw ValidationException::withMessages([
                'user_two_id' => ['A match with this user already exists.'],
            ]);
        }

        $match = UserMatch::create([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        return response()->json($match, 201);
    }

    public function index(Request $request)
    {
        $matches = UserMatch::where('user_one_id', $request->user()->id)
            ->orWhere('user_two_id', $request->user()->id)
            ->get();

        return response()->json($matches);
    }
}