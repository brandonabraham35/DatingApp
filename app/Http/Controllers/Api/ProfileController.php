<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(new UserResource($request->user()));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $request->user()->update($validated);

        return response()->json(new UserResource($request->user()));
    }
}