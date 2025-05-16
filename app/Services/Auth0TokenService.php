<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Auth0TokenService
{
    public function getToken($request): string
    {
       // dd($request->all());
        $cfg = config('services.auth0');
       // dd($cfg);
        $cacheKey = 'auth0_token_' . sha1($cfg['client_id']);


        return Cache::remember($cacheKey, now()->addSeconds($this->ttl($cfg)), function () use ($cfg,  $cacheKey, $request ) {
            $response = Http::asJson()->post(
                "{$cfg['base_url']}{$cfg['token_uri']}",
                $request->all()
            );

            $data = $response->throw()->json();
            // cache a few minutes less than expires_in
            $ttl = $data['expires_in'] > 300 ? $data['expires_in'] - 300 : $data['expires_in'];
            Cache::put($cacheKey, $data['access_token'], now()->addSeconds($ttl));

            return $data['access_token'];
        });
    }

    protected function ttl(array $cfg): int
    {
        // fallback if cache miss and no previous expires_in
        return 3600 - 300;
    }
}
