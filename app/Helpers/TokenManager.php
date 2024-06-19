<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
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
     * The credentials of passport.
     *
     * @var array
     */
    protected array $credentials;

    /**
     * TokenManager constructor.
     */
    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL');
        $this->accessToken = session('access_token');
        $this->credentials = [
            'grant_type' => 'password',
            'client_id' => config('passport.password_access_client.id'),
            'client_secret' => config('passport.password_access_client.secret'),
            'scope' => ''
        ];
    }

    /**
     * Generates an OAuth2 access token using password grant.
     *
     * @param string $username
     * @param string $password
     */
    public function generateAccessToken(string $username, string $password): void
    {
        $response = $this->requestAccessToken($username, $password);

        $this->accessToken = $response['access_token'] ?? null;

        session(['access_token' => $this->accessToken]);
    }

    /**
     * Makes a request to the OAuth2 token endpoint to obtain an access token.
     *
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function requestAccessToken(string $username, string $password): Response
    {
        $response = Http::asJson()->post("{$this->baseUrl}/oauth/token", [
            'username' => $username,
            'password' => $password,
            ...$this->credentials
        ]);

        return $response;
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

    /**
     * Retrieve the crentials value.
     *
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
