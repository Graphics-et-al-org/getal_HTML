<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;


class ValidateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        //  dd($request->headers->get('referer'));
        $header = $request->header('Authorization');
        if (!isset($header)) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        $secret =  env('JWT_SECRET', null);
        if(!$secret){
            return response()->json(['error' => 'JWT_SECRET not set'], 500);
        }
        $jwt = $request->bearerToken();

        try {
            // validate the token
            $decoded = JWT::decode($jwt, new Key($secret, 'HS256'));

            // Validate the URL as a little check
            if ($decoded->url !== $request->headers->get('referer')) {
                return response()->json(['error' => 'URL mismatch'], 403);
            }
            return $next($request);
        } catch (\Exception $e) {

            dd($e);
            // if anything goes wrong, send a generic unauthorised error
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
