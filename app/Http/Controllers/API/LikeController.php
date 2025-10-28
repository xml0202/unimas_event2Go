<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UpvoteDownvote;
use Illuminate\Http\Response;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function like(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
        ], [
            'event_id.required' => 'The event ID field is required.',
            'event_id.exists' => 'The selected event ID is invalid.',
        ]);
    
        // Get user from token
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }
    
        // Check if the like already exists
        if (UpvoteDownvote::where([
            ['event_id', $validatedData['event_id']],
            ['user_id', $user->id],
        ])->exists()) {
            return response()->json(['message' => 'Like already exists for this user and event'], Response::HTTP_CONFLICT);
        }
    
        // Create a new like record
        $like = UpvoteDownvote::create([
            'event_id' => $validatedData['event_id'],
            'user_id' => $user->id,
            'is_upvote' => true,
        ]);
    
        return response()->json([
            'message' => 'Event liked successfully',
            'like' => $like,
        ], Response::HTTP_CREATED);
    }
    
    public function unlike(Request $request)
    {
        // Validate only the event_id
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
        ], [
            'event_id.required' => 'The event ID field is required.',
            'event_id.exists' => 'The selected event ID is invalid.',
        ]);
    
        // Get user from token
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }
    
        // Find the like record for this user + event
        $like = UpvoteDownvote::where('event_id', $validatedData['event_id'])
            ->where('user_id', $user->id)
            ->first();
    
        if (!$like) {
            return response()->json(['message' => 'Like not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Delete the record
        $like->delete();
    
        return response()->json(['message' => 'Like deleted successfully'], Response::HTTP_OK);
    }
}
