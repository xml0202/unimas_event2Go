<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Comment;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return EventResource::collection($events);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
    
        $requestData['status'] = $request->approved ? 2 : 1;
    
        if ($request->has('attachment')) {
            $fileContent = base64_decode($request->attachment);
            $fileName = Str::random(20);
            Storage::disk('public')->put($fileName, $fileContent);
            $requestData['attachment'] = $fileName;
        }
    
        $event = Event::create($requestData);
    
        return response()->json(['message' => 'Event created successfully', 'event' => new EventResource($event)], 201);
    }

    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            return new EventResource($event);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    }
    
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string',
        ]);

        $event->update($validatedData);

        return response()->json(['message' => 'Event updated successfully', 'event' => $event], 200);
    }
    
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return Response::json(['message' => 'Event not found'], 404);
        }

        if ($event->attachment) {
            Storage::disk('public')->delete($event->attachment);
        }

        $event->delete();

        return Response::json(['message' => 'Event deleted successfully'], 200);
    }
    
    public function get_current_event_comments(Event $event)
    {
        $comments = $event->comments()->with('user')->get();

        return response()->json($comments);
    }
    
    
}
