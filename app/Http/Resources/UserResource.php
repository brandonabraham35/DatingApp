<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'username' => $this->username,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'location' => $this->location,
            'bio' => $this->bio,
            'profile_photo_url' => $this->profile_photo_path ? Storage::disk('public')->url($this->profile_photo_path) : null,
            'is_verified' => $this->is_verified,
            // Specifically exclude email and internal flags for safe public responses
        ];
    }
}
