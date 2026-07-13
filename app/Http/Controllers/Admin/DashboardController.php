<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserMatch;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_users' => User::count(),
            'total_matches' => UserMatch::count(),
        ];

        return response()->json($metrics);
    }
}