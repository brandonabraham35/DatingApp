<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

#[Fillable([
    'role',
    'username',
    'email',
    'password',
    'birth_date',
    'gender',
    'location',
    'bio',
    'profile_photo_path',
    'is_verified',
    'is_admin',
])]
#[Hidden(['password', 'remember_token', 'is_admin'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * Scope a query to only include providers.
     */
    public function scopeProviders($query)
    {
        return $query->where('role', 'provider');
    }

    /**
     * Scope a query to only include seekers.
     */
    public function scopeSeekers($query)
    {
        return $query->where('role', 'seeker');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_verified' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the user's age dynamically.
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    /**
     * Scope a query to filter users by geographic location.
     */
    public function scopeLocatedIn($query, $location)
    {
        return $query->where('location', $location);
    }
}
