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
    public function handle(Request $request, Closure $next)
    {
      //  dd($request->headers);
        $token = Str::chopStart($request->header('Authorization'), 'Bearer ');
        //dd($request->headers('Authorization'));
        try {
            // validate the user throught socialite
            $auth0user = Socialite::driver('auth0')->userFromToken($token);
            //@TODO log this?
            // check that this user exists...create the user
            $user = User::firstOrCreate([
                'provider' => 'auth0',
                'provider_id' => $auth0user->user['sub'],
            ], [
                'name' => $auth0user->user['name'],
                'email' => $auth0user->user['email'],
                'avatar' => $auth0user->user['picture'],
            ]);

            // Set the user for this request
            Auth::setUser($user);
           // dd($user);
            //$request->user = $user;
            return $next($request);
        } catch (\Exception $e) {
            dd($e);
           // if anything goes wrong, send a generic unauthorised error
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
