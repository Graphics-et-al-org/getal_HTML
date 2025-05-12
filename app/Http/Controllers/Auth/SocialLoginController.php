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
       // dd($provider);
        // There's a high probability something will go wrong
        //$user = null;
        // dd(
        //     Socialite::driver('auth0')
        //            ->stateless()
        //            ->redirect()
        //            ->getTargetUrl()
        //   );
        return Socialite::driver($provider)->redirect();
      //  dd($socialite);
        //dd($socialite->stateless()->redirect());
        //     return $socialite->redirect();

        // is there an issue with the
//        if (!$request->has('code') || $request->has('denied')) {
//            return redirect()->route(home_route())->withFlashDanger('Something went wrong :(');
//        }

        // // If the provider is not an acceptable third party than kick back
        // if (! in_array($provider, $this->socialiteHelper->getAcceptedProviders(), true)) {
        //     return redirect()->route(home_route())->withFlashDanger(__('auth.socialite.unacceptable', ['provider' => e($provider)]));
        // }

        /*
         * The first time this is hit, request is empty
         * It's redirected to the provider and then back here, where request is populated
         * So it then continues creating the user
         */

        // Create the user if this is a new social account or find the one that is already there.
        // try {
        //    // dd($this->userRepository);
        //     $user = $this->userRepository->findOrCreateProvider($this->getProviderUser($provider), $provider);
        // } catch (Ex $e) {
        //     return redirect()->route(home_route())->withFlashDanger($e->getMessage());
        // }

        // if ($user === null) {
        //     return redirect()->route(home_route())->withFlashDanger(__('exceptions.frontend.auth.unknown'));
        // }

        // // Check to see if they are active.
        // if (! $user->isActive()) {
        //     throw new GeneralException(__('exceptions.frontend.auth.deactivated'));
        // }

        // // Account approval is on
        // if ($user->isPending()) {
        //     throw new GeneralException(__('exceptions.frontend.auth.confirmation.pending'));
        // }

        // User has been successfully created or already exists
        //auth()->login($user, true);

        // Set session variable so we know which provider user is logged in as, if ever needed
       // session([config('access.socialite_session_name') => $provider]);

        //event(new UserLoggedIn(Auth::user()));

        // @TODO if first log in, collect demographic data

        // Return to the intended url or default to the class property
        //return redirect()->intended(route('home'));
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
