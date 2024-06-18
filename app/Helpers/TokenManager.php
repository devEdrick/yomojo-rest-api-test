<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

/**
 * Class TokenManager
 * @package App\Helpers
 */
class TokenManager
{
    /**
     * The base URL of the API.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * The access token retrieved and stored in session.
     *
     * @var string|null
     */
    protected ?string $accessToken;

    /**
     * TokenManager constructor.
     */
    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL');
        $this->accessToken = session('access_token');
    }

    /**
     * Generates an OAuth2 access token using password grant.
     *
     * @param string $username
     * @param string $password
     */
    public function generateAccessToken(string $username, string $password): void
    {
        $response = Http::asJson()->post("{$this->baseUrl}/oauth/token", [
            'grant_type' => 'password',
            'client_id' => config('passport.password_access_client.id'),
            'client_secret' => config('passport.password_access_client.secret'),
            'username' => $username,
            'password' => $password,
            'scope' => '',
        ]);

        $this->accessToken = $response['access_token'] ?? null;

        session(['access_token' => $this->accessToken]);
    }

    /**
     * Retrieve the current access token value.
     *
     * @return string|null
     */
    public function getAccessTokenValue(): ?string
    {
        return $this->accessToken;
    }
}
