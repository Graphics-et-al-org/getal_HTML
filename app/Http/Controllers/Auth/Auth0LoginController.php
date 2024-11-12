<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth0\Laravel\Controllers\CallbackControllerAbstract;
use Auth0\SDK\Auth0;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class Auth0LoginController extends CallbackControllerAbstract
{
    public function callback(Request $request): Redirector|RedirectResponse|Application
    {
        //dd($request);
        dd(Auth::user());
        try {
            $user = Auth::user();
            dd($user);
           // $auth0->exchange(route('profile'));
        } catch (\Exception $ex) {
            logger($ex->getMessage(), [$ex]);
            $auth0->clear();
        }

       // return redirect(route('profile'));
    }
}
