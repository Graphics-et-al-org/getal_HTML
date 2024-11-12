<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate  extends Authenticate
{

    protected function authenticate($request, array $guards)
    {

        if (empty($guards)) {
            $guards = ['web','auth0-session'];
        }
      //  dd($guards);
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }
        $this->unauthenticated($request, $guards);
    }
}
