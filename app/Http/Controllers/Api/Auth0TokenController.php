<?php

namespace App\Http\Controllers\Api;

use Ably\Auth;
use App\Http\Controllers\Controller;
use App\Services\Auth0TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Auth0TokenController extends Controller
{
    protected Request $_request;
    protected Auth0TokenService $_auth0TokenService;

    public function __construct(Request $request,   Auth0TokenService $auth0)
    {
        $this->_request = $request;
        $this->_auth0TokenService = $auth0;
    }

    /**
     * Return a fresh Auth0 machine-to-machine token.
     */
    public function getToken(): JsonResponse
    {
        $token = $this->_auth0TokenService->getToken($this->_request);

        return response()->json([
            'access_token' => $token,
            // optionally include expiry info if you track it
        ]);
    }
}
