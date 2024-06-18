<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

/**
 * Class RequestManager
 * @package App\Helpers
 */
class RequestManager
{
    /**
     * The base URL of the API.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * RequestManager constructor.
     */
    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL');
    }

    /**
     * Retrieve the OAuth2 access token from the session.
     *
     * @return string|null
     */
    protected function getToken(): ?string
    {
        return session('access_token');
    }

    /**
     * Perform a GET request.
     *
     * @param string $url
     * @return \Illuminate\Http\Client\Response
     */
    public function get(string $url): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->getToken(), 'Bearer')->get($this->baseUrl . $url);
    }

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $url, array $data): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->getToken(), 'Bearer')->post($this->baseUrl . $url, $data);
    }

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public function put(string $url, array $data): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->getToken(), 'Bearer')->put($this->baseUrl . $url, $data);
    }

    /**
     * Perform a DELETE request.
     *
     * @param string $url
     * @return \Illuminate\Http\Client\Response
     */
    public function delete(string $url): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->getToken(), 'Bearer')->delete($this->baseUrl . $url);
    }
}
