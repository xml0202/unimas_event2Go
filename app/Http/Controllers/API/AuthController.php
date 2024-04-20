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

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        // Generate a random 6-digit OTP
        $otp = mt_rand(100000, 999999);

        // Create the user
        $user = User::create([
            'name' =>  $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'otp' => $otp,
            'otp_expiry' => now()->addMinutes(5) // OTP will expire in 5 minutes
        ]);

        // Send the OTP to the user's email address
        Mail::to($user->email)->send(new OtpMail($otp));

        // Return response with token
        return response()->json(['message' => 'User registered successfully. Please check your email for OTP verification.'], 201);
    }
    
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $user = User::where('email', $fields['email'])->first();
        
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad Creds'    
            ], 401);
        }
        
        $token = $user->createToken('myapptoken')->plainTextToken;
        
        $response = [
          'user' => $user,
          'token' => $token
        ];
        
        return response($response, 201);
    }
    
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        
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

        // Generate token for the user
        $token = $user->createToken('myapptoken')->plainTextToken;

        // Return response with token
        return response()->json(['token' => $token, 'message' => 'Email verified successfully.'], 200);
    }

}
