<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::all();

        return response()->json(['notifications' => $notifications], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required',
            'body' => 'required',
            'sender_id' => 'nullable'
        ]);

        $notification = Notification::create([
            'event_id' => $validated['event_id'],
            'user_id' => $user->id,
            'sender_id' => $validated['sender_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        return response()->json([
            'message' => 'Notification created successfully.',
            'notification' => $notification
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        return response()->json(['notification' => $notification], 200);
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
        $notification = Notification::find($id);
    
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    
        // 🚫 Prevent others from editing
        // if ($notification->user_id !== $request->user()->id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);
    
        $notification->update($validated);
    
        return response()->json([
            'message' => 'Notification updated successfully.',
            'notification' => $notification,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully'], 200);
    }
}
