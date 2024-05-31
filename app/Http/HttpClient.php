<?php

namespace App\Http;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class HttpClient
{
    public static function base_client($access_token)
    {
        // $access_token = Session::get('api_access_token');
        $client = new Client([
            'base_uri' => env('API_BASE_URL'),
            'headers'  => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
            'http_errors' => false,
            'verify'      => false
        ]);
        $response = $client->get('/me');
        return $response->getBody()->getContents();
    }

    public static function login($username, $password)
    {
        $response = Http::asForm()->post(env('API_APP_URL') . '/token', [
            'grant_type'    => 'password',
            'client_id'     => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
            'username'      => $username,
            'password'      => $password
        ]);

        return $response->body();
    }

    // public static function introspect($username, $password)
    public static function introspect(Request $request)
    {
        $response = Http::asForm()->post(env('API_APP_URL') . '/token/introspect', [
            'grant_type'    => 'password',
            'client_id'     => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
            'username'      => $request->username,
            'password'      => $request->password,
            'token'         => $request->token
        ]);

        return $response->body();
    }
}