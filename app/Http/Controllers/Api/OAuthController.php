<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RequestManager;
use App\Helpers\TokenManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class OAuthController extends Controller
{
    protected $tokenManager;

    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @api {post} /api/oauth/token Issue Access Token
     * @apiName IssueToken
     * @apiGroup Authentication
     *
     * @apiBody {String} [username] The user's username.
     * @apiBody {String} [password] The user's password.
     *
     * @apiSuccess {String} access_token The issued access token.
     * @apiSuccess {String} token_type The token type (e.g., `Bearer`).
     * @apiSuccess {Number} expires_in The expiry duration of the token in seconds.
     * @apiSuccess {String} [refresh_token] The refresh token (only returned for some grant types).
     *
     * @apiError {String} error The error code or message.
     */

    public function issueToken(Request $request)
    {
        $request->merge($this->tokenManager->getCredentials());

        $tokenRequest = $request->create('/oauth/token', 'post', [  $request->all()]);
        $response = Route::dispatch($tokenRequest);

        return $response;
    }
}
