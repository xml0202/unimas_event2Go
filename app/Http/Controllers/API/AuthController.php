<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event; 
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use App\Http\Controllers\ExternalController;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.'
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Generate a random 6-digit OTP
        $otp = mt_rand(100000, 999999);
    
        // Create the user
        $user = User::create([
            'name' =>  $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'otp' => $otp,
            'otp_expiry' => now()->addMinutes(5) // OTP will expire in 5 minutes
        ]);
    
        $user->assignRole('User');
    
        // Send the OTP to the user's email address
        Mail::to($user->email)->send(new OtpMail($otp));
    
        // Return response with token
        return response()->json(['message' => 'User registered successfully. Please check your email for OTP verification.'], 201);
    }
    
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Generate a new random 6-digit OTP
        $otp = mt_rand(100000, 999999);

        // Update the user's OTP and expiry time
        $user->otp = $otp;
        $user->otp_expiry = now()->addMinutes(5); // OTP will expire in 5 minutes
        $user->save();

        // Send the OTP to the user's email address
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'OTP resent successfully. Please check your email for OTP verification.']);
    }
    
    // public function login(Request $request) {
    //     $fields = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string'
    //     ]);
        
    //     $user = User::where('email', $fields['email'])->first();
        
    //     if (!$user || !Hash::check($fields['password'], $user->password)) {
    //         Event::dispatch(new Failed('web', $user ? $user : null, ['email' => $fields['email']]));
    //         return response([
    //             'message' => 'Bad Creds'    
    //         ], 401);
    //     }
        
    //     Event::dispatch(new Login('web', $user, false));
        
    //     $token = $user->createToken('myapptoken')->plainTextToken;
    //     $roles = $user->roles()->pluck('name'); 
        
    //     $response = [
    //       'user' => $user,
    //       'role' => $roles,
    //       'token' => $token
    //     ];
        
    //     return response($response, 201);
    // }
    
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
    
        $user = User::where('email', $fields['email'])->first();
    
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            Event::dispatch(new Failed('web', $user ? $user : null, ['email' => $fields['email']]));
            return response([
                'message' => 'Bad Credentials'    
            ], 401);
        }
    
        Event::dispatch(new Login('web', $user, false));
    
        // Create a new token for the user using Passport
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;
        $expiresAt = $tokenResult->token->expires_at;
    
        $roles = $user->roles()->pluck('name');
    
        $response = [
            'user' => $user,
            'role' => $roles,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toDateTimeString()
        ];
    
        return response($response, 201);
    }
    
    // public function login(Request $request)
    // {

    //     // Validation
    //     $request->validate([
    //         // "email" => "required|string|email",
    //         "username" => "required",
    //         "password" => "required"
    //     ]);

    //     // Check user by "email" value
    //     // $user = User::where("email", $request->email)->first();

    //     $externalController = new ExternalController;
    //     $http_response    = $externalController->postRequest($request);
    //     $response         = json_decode($http_response->getContent());
    //     // dd($response);
    //     if (isset($response->active) && isset($response->access_token)) {
    //         $user_profile = HttpClient::base_client($response->access_token);
    //         $user_profile = json_decode($user_profile, true);

    //         $user = [
    //             'name'              => $user_profile['name'],
    //             'email'             => $user_profile['email'],
    //             'password'          => bcrypt($request->password),
    //             'email_verified_at' => date('Y-m-d H:i:s'),
    //             'username'          => $user_profile['username'],
    //         ];

    //         $auth_user = User::firstOrCreate(['username' => $user['username']], $user);
    //         $user_profile['user_id'] = $auth_user->id;

    //         $profile = $auth_user->profile()->firstOrCreate(['user_id' => $auth_user->id], $user_profile);
    //         // $request->authenticate();
    //         $token = $auth_user->createToken("myToken")->accessToken;
    //         return response()->json([
    //             "status" => true,
    //             "message" => "User logged in successfully",
    //             "token" => $token
    //         ]);
    //     } else {
    //         if ($response->errors == 419) {
    //             return response()->json([
    //                 "status" => false,
    //                 "message" => "Password didn't match"
    //             ]);
    //         } else {
    //             return response()->json([
    //                 "status" => false,
    //                 "message" => "Invalid credentials"
    //             ]);
    //         }
    //     }

    //     // Check user by "password" value

    //     // if (!empty($user)) {

    //     //     if (Hash::check($request->password, $user->password)) {

    //     //         // Auth Token value
    //     //         $token = $user->createToken("myToken")->accessToken;

    //     //         return response()->json([
    //     //             "status" => true,
    //     //             "message" => "User logged in successfully",
    //     //             "token" => $token
    //     //         ]);
    //     //     } else {

    //     //         return response()->json([
    //     //             "status" => false,
    //     //             "message" => "Password didn't match"
    //     //         ]);
    //     //     }
    //     // } else {

    //     //     return response()->json([
    //     //         "status" => false,
    //     //         "message" => "Invalid credentials"
    //     //     ]);
    //     // }
    // }
    
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        
        Event::dispatch(new Logout('web', auth()->user()));
        
        Auth::logout();
        
        return [
            'message' => 'Logged out'
        ];
    }
    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string'
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('otp_expiry', '>=', now())
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid OTP. Please try again.'], 401);
        }

        // OTP is valid, proceed with verification
        $user->email_verified_at = now(); // Mark email as verified
        $user->otp = null; // Clear OTP
        $user->otp_expiry = null; // Clear OTP expiry
        $user->save();

        $user->points()->create([
            'action' => 'user_registration',
            'points' => config('points.actions.user_registration'),
        ]);
        
        
        
        $user->updateTotalPoints();

        // Generate token for the user
        // $token = $user->createToken('myapptoken')->plainTextToken;

        // Return response with token
        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

}
