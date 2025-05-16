<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;


class EnsureAuth0TokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {
    //   //  dd($request->headers);
    //     $token = Str::chopStart($request->header('Authorization'), 'Bearer ');
    //     //dd($request->headers('Authorization'));
    //     try {
    //         // validate the user throught socialite
    //         $auth0user = Socialite::driver('auth0')->userFromToken($token);
    //         //@TODO log this?
    //         // check that this user exists...create the user
    //         $user = User::firstOrCreate([
    //             'provider' => 'auth0',
    //             'provider_id' => $auth0user->user['sub'],
    //         ], [
    //             'name' => $auth0user->user['name'],
    //             'email' => $auth0user->user['email'],
    //             'avatar' => $auth0user->user['picture'],
    //         ]);

    //         // Set the user for this request
    //         Auth::setUser($user);
    //        // dd($user);
    //         //$request->user = $user;
    //         return $next($request);
    //     } catch (\Exception $e) {
    //         dd($e);
    //        // if anything goes wrong, send a generic unauthorised error
    //         return response()->json(['error' => 'Unauthorised'], 401);
    //     }
    // }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Extract Bearer token
        $authHeader = $request->header('Authorization', '');
        if (! Str::startsWith($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        $token = Str::after($authHeader, 'Bearer ');

        // 2. Define a cache key based on the token
        $cacheKey = 'auth0_user_from_token:' . sha1($token);

        try {
            // 3. Attempt to retrieve the Socialite user from cache,
            //    otherwise call Auth0 once and cache the result.
            /** @var \Laravel\Socialite\Contracts\User $auth0user */
            $auth0user = Cache::remember(
                $cacheKey,
                // TTL: you can tune this to match token lifetime;
                // here we cache for 50 minutes as an example
                now()->addMinutes(50),
                fn() => Socialite::driver('auth0')->userFromToken($token)
            );

            // 4. Map (or create) your local user
            $user = User::firstOrCreate(
                [
                    'provider'    => 'auth0',
                    'provider_id' => $auth0user->getId(),               // same as $auth0user->user['sub']
                ],
                [
                    'name'   => $auth0user->getName(),
                    'email'  => $auth0user->getEmail(),
                    'avatar' => $auth0user->getAvatar(),
                ]
            );

            // 5. Bind the user into Laravel’s auth context
            Auth::setUser($user);
            $request->setUserResolver(fn() => $user);

            return $next($request);
        } catch (\Exception $e) {
            // You could also choose to clear the cache on certain errors:
            // Cache::forget($cacheKey);
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }


    // get the user metadata
    private function fetchUserMetadata($auth0Domain, $accessToken, $userId)
    {
        $url = "https://{$auth0Domain}/api/v2/users/{$userId}";

        $response = Http::withToken($accessToken)->get($url);

        if ($response->successful()) {
            $user = $response->json();

            return [
                'app_metadata' => $user['app_metadata'] ?? [],
                'user_metadata' => $user['user_metadata'] ?? [],
            ];
        }

        throw new \Exception('Failed to fetch user metadata: ' . $response->body());
    }
}
