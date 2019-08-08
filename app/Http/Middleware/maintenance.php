<?php

namespace App\Http\Middleware;

use Closure;

class maintenance
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
        if(settings()[0]->status ==="down")
        {
            return redirect("/maintenance");
        }
        return $next($request);
    }
}
