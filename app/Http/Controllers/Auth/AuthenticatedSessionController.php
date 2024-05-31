<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\ExternalController;
use App\Http\HttpClient;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }
    
    public function create_unimas(): View
    {
        return view('auth.login_unimas');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    
    public function store_unimas(Request $request): RedirectResponse
    {

        $http_response    = HttpClient::login($request->username, $request->password);
        $passport         = json_decode($http_response);
        session()->regenerate();
        if (isset($passport->access_token)) {
            $request->merge([
                'token' => $passport->access_token
            ]);
            $externalController = new ExternalController;
            $http_response    = $externalController->postRequest($request);
            $response         = json_decode($http_response->getContent());
            if (isset($response->active) && isset($response->access_token)) {
                $user_profile = HttpClient::base_client($response->access_token);
                $user_profile = json_decode($user_profile, true);
                $user = [
                    'name'              => $user_profile['name'],
                    'email'             => $user_profile['email'],
                    'password'          => bcrypt($request->password),
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'username'          => $user_profile['username'],
                ];

                Session::put('api_access_token', $passport->access_token);
                $auth_user = User::firstOrCreate(['username' => $user['username']], $user);
                $user_profile['user_id'] = $auth_user->id;
                
                $profile = $auth_user->profile()->firstOrCreate(['user_id' => $auth_user->id], $user_profile);
                if ($profile->category == "STAFF")
                {
                    $auth_user->assignRole('Agency');
                }
                else
                {
                    $auth_user->assignRole('User');
                }
                // $request->authenticate();
                Auth::login($auth_user);
                $request->session()->put('user', $auth_user->toArray());
            } else {
                // if ($response->errors == 419) {
                Session::flash('error', 'Session Expired');
                return redirect()->route('login_unimas');
            }
        } else {
            Session::flash('error', 'Invalid login credentials');
            return redirect()->route('login_unimas');
        }

        // dd(session()->all());
        return redirect()->route('home');
    }
    
    // public function store(Request $request): RedirectResponse
    // {

    //     $http_response    = HttpClient::login($request->username, $request->password);
    //     $passport         = json_decode($http_response);
    //     session()->regenerate();
    //     if (isset($passport->access_token)) {
    //         $request->merge([
    //             'token' => $passport->access_token
    //         ]);
    //         $externalController = new ExternalController;
    //         $http_response    = $externalController->postRequest($request);
    //         $response         = json_decode($http_response->getContent());
    //         if (isset($response->active) && isset($response->access_token)) {
    //             $user_profile = HttpClient::base_client($response->access_token);
    //             $user_profile = json_decode($user_profile, true);
    //             $user = [
    //                 'name'              => $user_profile['name'],
    //                 'email'             => $user_profile['email'],
    //                 'password'          => bcrypt($request->password),
    //                 'email_verified_at' => date('Y-m-d H:i:s'),
    //                 'username'          => $user_profile['username'],
    //             ];

    //             Session::put('api_access_token', $passport->access_token);
    //             $auth_user = User::firstOrCreate(['username' => $user['username']], $user);
    //             $user_profile['user_id'] = $auth_user->id;

    //             $profile = $auth_user->profile()->firstOrCreate(['user_id' => $auth_user->id], $user_profile);
    //             // $request->authenticate();
    //             Auth::login($auth_user);
    //             $request->session()->put('user', $auth_user->toArray());
    //         } else {
    //             // if ($response->errors == 419) {
    //             Session::flash('error', 'Session Expired');
    //             return redirect()->route('login');
    //         }
    //     } else {
    //         Session::flash('error', 'Invalid login credentials');
    //         return redirect()->route('login');
    //     }

    //     // dd(session()->all());
    //     return redirect()->route('home');
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
