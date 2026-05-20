<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUserMiddleware {
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        if ($user && $user->is_banned) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['login' => 'Your account has been suspended. Reason: ' . $user->ban_reason]);
        }
        if ($user) {
            $user->updateQuietly(['last_seen_at' => now()]);
        }
        return $next($request);
    }
}
