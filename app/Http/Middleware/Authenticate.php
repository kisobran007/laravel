<?php

namespace App\Http\Middleware;
use Session;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        Session::put('oldURL', $request->url());
        return route('getsignin');
    }
}
