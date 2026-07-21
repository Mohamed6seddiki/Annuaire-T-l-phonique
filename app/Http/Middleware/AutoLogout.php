<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AutoLogout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $timeout = config('session.lifetime') * 60;

            if (session()->has('last_activity')) {
                if (time() - session('last_activity') > $timeout) {
                    Auth::logout();
                    session()->flush();

                    return redirect()
                        ->route('login')
                        ->with(
                            'message',
                            'Vous avez été automatiquement déconnecté en raison de votre inactivité.'
                        );
                }
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}