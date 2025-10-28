<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmarks = Bookmark::all();
        return response()->json($bookmarks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user(); // ← Authenticated user from token
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ], [
            'event_id.required' => 'The event ID field is required.',
            'event_id.exists' => 'The selected event ID is invalid.',
        ]);
    
        $event_id = $validated['event_id'];
        $user_id = $user->id; // ← Automatically use logged-in user
    
        // Check if the bookmark already exists
        $existingBookmark = Bookmark::where('event_id', $event_id)
                                    ->where('user_id', $user_id)
                                    ->first();
    
        if ($existingBookmark) {
            return response()->json([
                'message' => 'Bookmark already exists for this user and event'
            ], Response::HTTP_CONFLICT);
        }
    
        try {
            $bookmark = Bookmark::create([
                'event_id' => $event_id,
                'user_id' => $user_id,
            ]);
    
            return response()->json([
                'message' => 'Bookmark created successfully',
                'bookmark' => $bookmark
            ], Response::HTTP_CREATED);
    
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create bookmark',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($bookmark);
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
        $request->validate([
            'event_id' => 'exists:events,id',
            'user_id' => 'exists:users,id',
        ]);

        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark not found'], Response::HTTP_NOT_FOUND);
        }

        $bookmark->update($request->all());

        return response()->json(['message' => 'Bookmark updated successfully', 'bookmark' => $bookmark], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark not found'], Response::HTTP_NOT_FOUND);
        }

        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted successfully'], Response::HTTP_NO_CONTENT);
    }
    
    public function addBookmark(Request $request)
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
    
        $existingBookmark = Bookmark::where([
            ['event_id', $validatedData['event_id']],
            ['user_id', $validatedData['user_id']],
        ])->exists();
    
        if ($existingBookmark) {
            return response()->json(['message' => 'Bookmark already exists for this user and event'], Response::HTTP_CONFLICT);
        }
    
        $bookmark = Bookmark::create($validatedData);
    
        return response()->json(['message' => 'Event bookmarked successfully', 'bookmark' => $bookmark], Response::HTTP_CREATED);
    }
    
    public function removeBookmark(Request $request)
    {
        // Validate JSON request data
        $request->validate([
            'event_id' => 'required|exists:bookmarks,event_id',
            'user_id' => 'required|exists:bookmarks,user_id',
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
            $bookmark = Bookmark::where('event_id', $eventId)->where('user_id', $userId)->firstOrFail();
            
            // Delete the bookmark
            $bookmark->delete();
            
            return response()->json(['message' => 'Bookmark deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Bookmark not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
