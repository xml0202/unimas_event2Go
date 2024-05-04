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
            'user_id' => 'required|exists:users,id',
        ], [
            'event_id.required' => 'The event ID field is required.',
            'event_id.exists' => 'The selected event ID is invalid.',
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user ID is invalid.',
        ]);
    
        // Check if the like already exists
        if (UpvoteDownvote::where([
            ['event_id', $validatedData['event_id']],
            ['user_id', $validatedData['user_id']],
        ])->exists()) {
            return response()->json(['message' => 'Like already exists for this user and event'], Response::HTTP_CONFLICT);
        }
    
        // Create a new like record
        $like = UpvoteDownvote::create($validatedData + ['is_upvote' => true]);
    
        return response()->json(['message' => 'Event liked successfully', 'like' => $like], Response::HTTP_CREATED);
    }
    
    public function unlike(Request $request)
    {
        // Validate JSON request data
        $request->validate([
            'event_id' => 'required|exists:upvote_downvotes,event_id',
            'user_id' => 'required|exists:upvote_downvotes,user_id',
        ], [
            'event_id.required' => 'The event ID field is required.',
            'event_id.exists' => 'The selected event ID is invalid.',
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user ID is invalid.',
        ]);
    
        // Extract event ID and user ID from JSON payload
        $eventId = $request->input('event_id');
        $userId = $request->input('user_id');
    
        try {
            // Find the bookmark by event ID and user ID
            $like = UpvoteDownvote::where('event_id', $eventId)->where('user_id', $userId)->firstOrFail();
            
            // Delete the bookmark
            $like->delete();
            
            return response()->json(['message' => 'Like deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Like not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
