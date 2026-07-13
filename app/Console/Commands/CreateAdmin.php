<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:create-admin {email} {password} {username=admin}')]
#[Description('Create a new administrator account')]
class CreateAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $username = $this->argument('username');

        $user = \App\Models\User::create([
            'role' => 'provider',
            'username' => $username,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'is_admin' => true,
            'is_verified' => true,
        ]);

        $this->info("Admin user created successfully. Email: {$user->email}");
    }
}