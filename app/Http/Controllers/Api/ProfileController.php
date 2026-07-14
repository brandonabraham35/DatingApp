<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(new UserResource($request->user()));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'username')->ignore($request->user()->id)],
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            $validated['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        unset($validated['photo']);
        $user->update($validated);

        return response()->json(new UserResource($user->fresh()));
    }
}
