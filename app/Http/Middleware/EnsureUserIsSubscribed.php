<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'seeker') {
            if (!$user->subscribed('premium')) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'Payment Required.'], 403);
                }

                return redirect()->to('/billing');
            }
        }

        return $next($request);
    }
}
