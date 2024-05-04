<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserInfo;

class UserInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserInfo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mobile_no' => 'required|string|max:20|unique:user_infos',
            'email' => 'required|string|email|max:50|unique:user_infos',
            'addr_line_1' => 'required|string|max:255',
            'addr_line_2' => 'nullable|string|max:255',
            'postcode' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gender' => 'required|integer|between:0,1', // Validate gender as an integer between 1 and 2
        ], [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'mobile_no.required' => 'The mobile number is required.',
            'mobile_no.unique' => 'The mobile number has already been taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'addr_line_1.required' => 'The address line 1 is required.',
            'postcode.required' => 'The postcode is required.',
            'city.required' => 'The city is required.',
            'state.required' => 'The state is required.',
            'country.required' => 'The country is required.',
            'gender.required' => 'The gender is required.',
            'gender.integer' => 'The gender must be an integer.',
            'gender.between' => 'The gender must be either 0 or 1.',
        ]);

        // Create a new user_info record
        $userInfo = UserInfo::create($validatedData);
        
        return response()->json($userInfo, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserInfo $user_info)
    {
        return $user_info;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserInfo $user_info)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'mobile_no' => 'required|string|max:20|unique:user_infos,mobile_no,' . $user_info->id,
            'email' => 'required|string|email|max:50|unique:user_infos,email,' . $user_info->id,
            'addr_line_1' => 'required|string|max:255',
            'addr_line_2' => 'nullable|string|max:255',
            'postcode' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gender' => 'required|integer|between:0,1', // Validate gender as an integer between 0 and 1
        ], [
            'mobile_no.required' => 'The mobile number is required.',
            'mobile_no.unique' => 'The mobile number has already been taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'addr_line_1.required' => 'The address line 1 is required.',
            'postcode.required' => 'The postcode is required.',
            'city.required' => 'The city is required.',
            'state.required' => 'The state is required.',
            'country.required' => 'The country is required.',
            'gender.required' => 'The gender is required.',
            'gender.integer' => 'The gender must be an integer.',
            'gender.between' => 'The gender must be either 0 or 1.',
        ]);
    
        // Update the user_info record
        $user_info->update($validatedData);
        
        return response()->json($user_info, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_info = UserInfo::find($id);
        if (!$user_info) {
            return response()->json(['message' => 'User info not found'], Response::HTTP_NOT_FOUND);
        }
        
        $user_info->delete();
        return response()->json(['message' => 'User info deleted successfully'], Response::HTTP_OK);
    }
}
