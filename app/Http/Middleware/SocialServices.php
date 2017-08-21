<?php

namespace App\Http\Middleware;

use Closure;

class SocialServices
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!in_array(strtolower($request->service), config('social.services'), true)){
            return redirect()->back();
        }

        return $next($request);
    }
}
