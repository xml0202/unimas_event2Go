<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\HttpClient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ExternalController extends Controller
{
    public function postRequest(Request $request)
    {

        // $http_response    = HttpClient::login($request->username, $request->password);
        // $passport         = json_decode($http_response);

        // if (isset($passport->access_token)) {
        if (isset($request->token)) {

            // if (!Session::isStarted()) {
            //     Session::start();
            // }
            // Session::put('api_access_token', $passport->access_token);
            $introspect = json_decode(HttpClient::introspect($request));
            if (isset($introspect->active) && $introspect->active) {

                return response()->json([
                    'message'      => 'Token & credential checked successfully',
                    // 'access_token' => $passport->access_token,
                    'access_token' => $request->token,
                    'active'       => $introspect->active
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Session Expired.',
                    'errors' => 419,
                ], 419); // 419 - Authentication Timeout
            }
        } else {
            // dd($passport);
            return response()->json([
                'message' => 'Invalid login credentials.',
                'errors' => 401,
            ], 401); // 401 - Unauthorized
        }
    }
}