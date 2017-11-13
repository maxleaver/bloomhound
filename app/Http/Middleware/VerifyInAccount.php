<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class VerifyInAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $model)
    {
        if ($request->route($model)->account->id !== Auth::user()->account->id) {
            abort(404);
        }

        return $next($request);
    }
}
