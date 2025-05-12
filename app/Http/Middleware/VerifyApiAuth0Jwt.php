<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

/**
 * Middleware to verify Auth0 JWT tokens.
 *
 * This middleware checks the validity of the JWT token provided in the request.
 * It fetches the JWKS from Auth0, decodes and verifies the token, and checks
 * the issuer and audience. Optionally, it can enforce scopes based on route defaults.
 *
 * Used for API calls
 */
class VerifyApiAuth0Jwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // 1. Get the Bearer token via Laravel helper
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // 2. Fetch JWKS (cache for 24h)
        //dd(config('services.auth0'));
        $jwksUri = config('services.auth0.base_url') . '/.well-known/jwks.json';
        // dd(config('services.auth0.base_url'));
        $jwksJson    = Cache::remember('auth0_jwks', now()->addSecond(), function () use ($jwksUri) {
            $resp = (new Client())->get($jwksUri);
            return json_decode($resp->getBody(), true);
        });
        $keySet    = JWK::parseKeySet($jwksJson);

        // 3. Decode & verify
        try {
            // You can set leeway if needed: JWT::$leeway = 60;
            $decoded = JWT::decode(
                $token,
                $keySet,
            );
            //  dd($decoded);
        } catch (UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        }

        // 4. Verify issuer & audience
        $expectedIss = Config::get('services.auth0.base_url') . '/';
        $expectedAud = Config::get('services.auth0.audience');
        // dd(Config::get('services.auth0'));
        //dd(env('AUTH0_DOMAIN'));

        if ($decoded->iss !== $expectedIss) {
            return response()->json(['error' => 'Invalid issuer'], 401);
        }
        $aud = is_array($decoded->aud) ? $decoded->aud : [$decoded->aud];
        if (! in_array($expectedAud, $aud, true)) {
            return response()->json(['error' => 'Invalid audience'], 401);
        }

        // 5. Optionally enforce scopes (route “scopes” default)
        if ($scopes = $request->route()->defaults['scopes'] ?? null) {
            $required = explode('|', $scopes);
            $granted  = explode(' ', $decoded->scope ?? '');
            if (count(array_diff($required, $granted)) > 0) {
                return response()->json(['error' => 'Insufficient scope'], 403);
            }
        }

        //Set the internal user
        //  dd($decoded->);
        try {
            $user = User::where([
                'provider' => 'auth0',
                'provider_id' => $decoded->azp,
            ])->firstOrFail();
        } catch (Exception $e) {
            //dd($e);
            return response()->json(['error' => 'Not a registered user'], 401);
        }
        // dd($user);

        // 6. Bind the payload so controllers can access it
        $request->attributes->set('jwt_payload', $decoded);

        return $next($request);
    }
}
