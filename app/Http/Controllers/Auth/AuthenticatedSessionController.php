<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use App\Http\HttpClient;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\ExternalController;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Redirect user to OAuth provider for authentication
     */
    public function redirect(): RedirectResponse
    {
        $state = Str::random(40);

        $params = [
            'client_id' => env('OAUTH_CLIENT_ID', 'event2go-web'),
            'response_type' => 'code',
            'redirect_uri' => env('OAUTH_REDIRECT_URI', 'https://event.kuchingitsolution.net/'),
            'scope' => 'openid profile email',
            'state' => $state
        ];

        session(['oauth_state' => $state]);

        $baseUrl = env('OAUTH_BASE_URL', 'https://id.unimas.my/realms/UNIMAS/protocol/openid-connect');
        $url = $baseUrl . '/auth?' . http_build_query($params);

        return redirect($url);
    }

    public function handleRootCallback(Request $request): RedirectResponse
    {
        
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        if ($request->has('code')) {
            return $this->callback($request);
        }

        return redirect()->route('home');
    }

    public function callback(Request $request): RedirectResponse
    {
        Log::info('OAuth Callback - Incoming Request', $request->all());
    
        if ($request->state !== session('oauth_state')) {
            Log::error('Invalid state parameter', [
                'request_state' => $request->state,
                'session_state' => session('oauth_state'),
            ]);
            Session::flash('error', 'Invalid state parameter. Please try again.');
            return redirect()->route('home');
        }
    
        if ($request->has('error')) {
            $error_message = $request->error_description ?? 'Authorization failed: ' . $request->error;
            Log::error('Authorization Error', $request->all());
            Session::flash('error', $error_message);
            return redirect()->route('home');
        }
    
        if (!$request->has('code')) {
            Log::error('Authorization code not received', $request->all());
            Session::flash('error', 'Authorization code not received');
            return redirect()->route('home');
        }
    
        try {
            $baseUrl = env('OAUTH_BASE_URL', 'https://id.unimas.my/realms/UNIMAS/protocol/openid-connect');
    
            $payload = [
                'grant_type' => 'authorization_code',
                'client_id' => env('API_CLIENT_ID', 'event2go-web'),
                'client_secret' => env('API_CLIENT_SECRET'),
                'code' => $request->code,
                'redirect_uri' => env('OAUTH_REDIRECT_URI', 'https://event.kuchingitsolution.net/'),
                'scope' => 'openid profile email phone address offline_access microprofile-jwt web-origins acr roles extra',
            ];
    
            Log::info('Token Request Payload', $payload);
    
            $response = Http::asForm()->post($baseUrl . '/token', $payload);
    
            Log::info('Token Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
    
            if (!$response->successful()) {
                Session::flash('error', 'Failed to obtain access token: ' . $response->body());
                return redirect()->route('home');
            }
    
            $tokenData = $response->json();
    
            if (!isset($tokenData['access_token'])) {
                Log::error('Access token missing', $tokenData);
                Session::flash('error', 'Access token not received');
                return redirect()->route('home');
            }
    
            if (isset($tokenData['id_token'])) {
                $idTokenParts = explode('.', $tokenData['id_token']);
                if (count($idTokenParts) === 3) {
                    $claims = json_decode(base64_decode($idTokenParts[1]), true);
                    Log::info('Decoded ID Token Claims', $claims);
                }
                Session::put('id_token', $tokenData['id_token']);
            }
    
            $userResponse = Http::withToken($tokenData['access_token'])
                ->get($baseUrl . '/userinfo');
    
            Log::info('User Info Response', [
                'status' => $userResponse->status(),
                'body' => $userResponse->body(),
            ]);
    
            if (!$userResponse->successful()) {
                Session::flash('error', 'Failed to get user information');
                return redirect()->route('home');
            }
    
            $userInfo = $userResponse->json();
    
            $userData = [
                'name' => $userInfo['name'] ?? $userInfo['preferred_username'] ?? 'Unknown',
                'email' => $userInfo['email'] ?? ($userInfo['sub'] . '@example.com'),
                'email_verified_at' => now(),
                'username' => $userInfo['preferred_username'] ?? $userInfo['sub'],
                'password' => bcrypt(Str::random(16)),
            ];
    
            $uniqueField = !empty($userInfo['email']) ? 'email' : 'username';
            $uniqueValue = !empty($userInfo['email'])
                ? $userInfo['email']
                : ($userInfo['preferred_username'] ?? $userInfo['sub']);
    
            Log::info('User Data for DB', $userData);
    
            $auth_user = User::firstOrCreate(
                [$uniqueField => $uniqueValue],
                $userData
            );
            
            if (!$auth_user->hasRole('User')) {
                $auth_user->assignRole('User');
            }
    
            Session::put('api_access_token', $tokenData['access_token']);
            if (isset($tokenData['refresh_token'])) {
                Session::put('refresh_token', $tokenData['refresh_token']);
            }
    
            session()->forget('oauth_state');
    
            if (method_exists($auth_user, 'profile')) {
                
                $profileData = [
                    'user_id'        => $auth_user->id,
                    'username'       => $auth_user->username,
                    'fullname'       => $userInfo['name'] ?? null,
                    'email'          => $userInfo['email'] ?? null,
                    'altEmail'       => $userInfo['altEmail'] ?? null,
                    'universityId'   => $userInfo['preferred_username'] ?? null,
                    'departmentCode' => $userInfo['departmentCode'] ?? null,
                    'departmentName' => $userInfo['departmentName'] ?? null,
                    'salutation'     => $userInfo['salutation'] ?? null,
                    'phoneNo'        => $userInfo['phoneNo'] ?? null,
                    'officeCode'     => $userInfo['officeCode'] ?? null,
                    'officeName'     => $userInfo['officeName'] ?? null,
                    'category'       => $userInfo['category'] ?? null,
                    'categoryCode'   => $userInfo['categoryCode'] ?? null,
                    'nationalId'     => $userInfo['nationalId'] ?? null,
                    'staff'          => $userInfo['staff'] ?? null,
                    'picture'        => $userInfo['picture'] ?? null,
                    'authorities'    => $userInfo['authorities'] ?? [],
                    'extra'          => $userInfo, // keep whole response for reference
                ];
                
                if ($auth_user->profile) {
                    $auth_user->profile->update($profileData);
                } else {
                    $auth_user->profile()->create($profileData);
                }
            }
    
            Auth::login($auth_user);
            $tokenResult = Auth::user()->createToken('api-token');
            $token = $tokenResult->accessToken; 
            session(['laravel_token' => $token]);

            session(['user' => $auth_user->toArray()]);
            session()->regenerate();
    
            Log::info('User authenticated successfully', [
                'user_id' => $auth_user->id,
                'email' => $auth_user->email,
                'token' => $token
            ]);
    
            return redirect()->route('home')->cookie('laravel_token', $token, 60);
    
        } catch (\Exception $e) {
            Log::error('Authentication Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            Session::flash('error', 'Authentication failed: ' . $e->getMessage());
            return redirect()->route('home');
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $idToken = session('id_token');
        $redirectUri = env('OAUTH_REDIRECT_URI', 'https://event.kuchingitsolution.net/');
    
        // Local Laravel logout
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // If we have an id_token, log out from Keycloak too
        if ($idToken) {
            $logoutUrl = 'https://id.unimas.my/realms/UNIMAS/protocol/openid-connect/logout'
                . '?id_token_hint=' . urlencode($idToken)
                . '&post_logout_redirect_uri=' . urlencode($redirectUri);
    
            return redirect($logoutUrl);
        }
    
        // Fallback: just go home
        return redirect('/home');
    }
}
