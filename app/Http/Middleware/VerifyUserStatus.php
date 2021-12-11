<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;

class VerifyUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()){
            if ( auth()->user()->status ) {
                return $next($request);
            }
            else{
                Auth::guard(config('fortify.guard', null))->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                $request->session()->flash('flash.banner', 'Your Account Has Been Suspended! Please Contact Website Admin for More Information.');
                $request->session()->flash('flash.bannerStyle', 'danger');

                return app(LogoutResponse::class);
            }
        }
        abort(404);
    }
}
