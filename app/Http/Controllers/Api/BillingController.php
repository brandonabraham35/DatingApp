<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        return $user->checkout('price_premium_monthly_id', [
            'success_url' => url('/billing/success'),
            'cancel_url' => url('/billing/cancel'),
        ]);
    }

    public function portal(Request $request)
    {
        $user = $request->user();
        return $user->redirectToBillingPortal(url('/dashboard'));
    }
}
