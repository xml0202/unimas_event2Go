<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Comment::with('user', 'event')->get();
        return Comment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Authenticated user from Bearer token
    
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $validated = $validator->validated();
    
        // ✅ Automatically assign user_id from token
        $comment = Comment::create([
            'comment'   => $validated['comment'],
            'event_id'  => $validated['event_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id'   => $user->id,
        ]);
    
        // Eager load related user & event
        $comment->load('user.profile', 'event');
    
        return response()->json([
            'message' => 'Comment posted successfully.',
            'comment' => $comment
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return $comment->load('user', 'event');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $user = $request->user(); // Authenticated user from Bearer token
    
        // ✅ Ensure the user owns the comment
        if ((int)$comment->user_id !== (int)$user->id) {
            return response()->json([
                'message' => 'Unauthorized: you can only edit your own comments.'
            ], 403);
        }
    
        // ✅ Validate input
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);
    
        // ✅ Update only allowed fields
        $comment->update([
            'comment' => $validated['comment'],
        ]);
    
        // ✅ Eager load relationships for response
        $comment->load('user.profile', 'event');
    
        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => $comment,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
