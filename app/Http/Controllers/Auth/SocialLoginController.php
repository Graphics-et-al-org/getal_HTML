<?php

namespace App\Http\Controllers\Auth;

use App\Events\Frontend\Auth\UserLoggedIn;
use App\Exceptions\GeneralException;
use App\Helpers\Auth\SocialiteHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class SocialLoginController.
 */
class SocialLoginController extends Controller
{


    /**
     * @param Request $request
     * @param $provider
     *
     * @throws GeneralException
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function sociallogin(Request $request, $provider)
    {

        return Socialite::driver($provider)->redirect();

    }

    public function callback(){

        $user = Socialite::driver('auth0')->user();

        $user = User::firstOrCreate([
            'provider' => 'auth0',
            'provider_id' => $user->user['sub'],
        ], [
            'name' => $user->user['name'],
            'email' => $user->user['email'],
            'avatar' => $user->user['picture'],
        ]);

        // Set the user for this request
        Auth::login($user, true);
        return redirect()->intended(route('home'));
        //dd($user);
    }


}
