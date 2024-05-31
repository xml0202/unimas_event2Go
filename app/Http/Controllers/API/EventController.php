<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Attendee;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserInfo;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\AgencyUser;
use App\Models\Officer;
use App\Models\VIP;
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
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'attachment' => 'required|array',
            'attachment.*' => 'required|string', // Assuming attachments are base64-encoded strings
            'introduction' => 'required|string',
            'organized_by' => 'required|string',
            'in_collaboration' => 'nullable|string',
            'program_objective' => 'required|string',
            'program_impact' => 'required|string',
            'invitation' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            // 'category' => 'required|string',
            'category' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Category::where('category_name', $value)->exists()) {
                        $fail('The selected category is invalid.');
                    }
                },
            ],
            'location' => 'required|string',
            'max_user' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'earn_points' => 'nullable|integer|min:0',
            'approved' => 'required|boolean',
            'approval' => 'nullable|string',
            'comment_enabled' => 'required|boolean',
        ]);
    
        // Decode and store attachments
        $attachments = [];
        foreach ($validatedData['attachment'] as $attachmentData) {
            // Decode the base64-encoded attachment data
            $fileContent = base64_decode($attachmentData);
        
            // Generate a random filename with a file extension
            $fileName = Str::random(20) . '.jpg'; // For example, you can assume it's a JPEG file
        
            // Store the attachment file in the filesystem
            Storage::disk('public')->put($fileName, $fileContent);
        
            // Add the filename to the list of attachments
            $attachments[] = $fileName;
        }
    
        // Update the validated data with the decoded attachment file names
        $validatedData['attachment'] = $attachments;
        $adminId = AgencyUser::where('user_id', $validatedData['user_id'])->value('admin_id');
        $validatedData['admin_id'] = $adminId;
        $validatedData['status'] = $request->approval ? 2 : 1;
    
        // Create the event with the validated data
        $event = Event::create($validatedData);
    
        return response()->json(['message' => 'Event created successfully', 'event' => new EventResource($event)], 201);
    }


    public function show($id)
    {
        try {
            $event = Event::with('comments')->withCount('likes', 'bookmarks', 'attendees')->findOrFail($id);
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
    
    public function get_joined_events($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $events = Event::whereHas('attendees', function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('status', 1);
                })
                ->withCount(['attendees', 'likes', 'bookmarks'])
                ->with('comments')
                ->get();
                
        if ($events->isEmpty()) {
            return response()->json(['message' => 'No events found for the user'], 404);
        }
        
        return response()->json($events);
    }
    
    public function getOngoingEvents()
    {
        $ongoingEvents = Event::where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->where('status', 1)
            ->get();

        return response()->json(['ongoing_events' => $ongoingEvents], 200);
    }
    
    public function getOngoingEventsByYear($year)
    {
        $ongoingEvents = Event::whereYear('start_datetime', $year)
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->where('status', 1)
            ->orderBy('start_datetime')
            ->get();

        return response()->json(['ongoing_events' => $ongoingEvents], 200);
    }
    
    public function getUpcomingEvents()
    {
        $upcomingEvents = Event::where('start_datetime', '>=', now())
            ->where('status', 1)
            ->orderBy('start_datetime')
            ->get();

        return response()->json(['upcoming_events' => $upcomingEvents], 200);
    }
    
    public function getUpcomingEventsByYear($year)
    {
        $upcomingEvents = Event::whereYear('start_datetime', $year)
            ->where('start_datetime', '>=', now())
            ->where('status', 1)
            ->orderBy('start_datetime')
            ->get();

        return response()->json(['upcoming_events' => $upcomingEvents], 200);
    }
    
    public function getSixOngoingEvents()
    {
        $sixOngoingEvents = Event::where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->where('status', 1)
            ->limit(6)
            ->get();

        return response()->json(['six_ongoing_events' => $sixOngoingEvents], 200);
    }
    
    public function getSixUpcomingEvents()
    {
        $sixUpcomingEvents = Event::where('start_datetime', '>=', now())
            ->where('status', 1)
            ->orderBy('start_datetime')
            ->limit(6)
            ->get();

        return response()->json(['six_upcoming_events' => $sixUpcomingEvents], 200);
    }
    
    public function getUserNotifications($userId)
    {
        // Retrieve the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the notifications for the user
        $notifications = Notification::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->get();

        return response()->json(['notifications' => $notifications], 200);
    }
    
    public function getUserPastEvents($userId)
    {
        // Retrieve the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the user's past events
        $pastEvents = Event::where('end_datetime', '<', now())
            ->whereHas('attendees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return response()->json(['past_events' => $pastEvents], 200);
    }
    
    public function getUserJoinedEvents($userId)
    {
        // Retrieve the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the user's past events
        $joinedEvents = Event::where('end_datetime', '>=', now())
            ->whereHas('attendees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return response()->json(['joined_events' => $joinedEvents], 200);
    }
    
    public function getEventAttendees($eventId)
    {
        // Retrieve the event
        $event = Event::with('attendees')->find($eventId);
    
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        // Retrieve the attendees for the event with user details
        $attendees = $event->attendees;
    
        return response()->json(['attendees' => $attendees], 200);
    }
    
    public function getAllUsersPoints()
    {
        // Retrieve all users with their points
        $usersPoints = User::select('id', 'name', 'total_points')->get();

        return response()->json(['users_points' => $usersPoints], 200);
    }
    
    public function getUserPoints($userId)
    {
        // Retrieve the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the user's points
        // $userPoints = $user->total_points;

        return response()->json(['user' => $user], 200);
    }
    
    public function getHighestPointsUsers()
    {
        // Retrieve the 10 users with the highest points
        $highestPointsUsers = User::
            orderBy('total_points', 'desc')
            ->take(10)
            ->get();

        return response()->json(['highest_points_users' => $highestPointsUsers], 200);
    }
    
    public function getUserInfo($userId)
    {
        // Retrieve the user info for the specified user ID
        $userInfo = UserInfo::where('user_id', $userId)->first();

        if (!$userInfo) {
            return response()->json(['message' => 'User info not found'], 404);
        }

        return response()->json(['user_info' => $userInfo], 200);
    }
    
    public function joinEvent(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'gender' => 'required',
            'mobile_no' => 'required',
            'email' => 'required',
            'addr_line_1' => 'required',
            'addr_line_2' => 'required',
            'postcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            // Add validation rules for other fields as needed
        ]);
    
        // Retrieve the user and event
        $user = User::findOrFail($validatedData['user_id']);
        $event = Event::findOrFail($validatedData['event_id']);
        
        if (now() > $event->end_datetime) {
            return response()->json(['message' => 'Event has already ended'], 400);
        }
    
        // Check if the user is already attending the event
        if ($user->events()->where('event_id', $event->id)->exists()) {
            return response()->json(['message' => 'User is already attending the event'], 400);
        }
        
        $currentAttendees = $event->attendees()->count();
        if ($currentAttendees >= $event->max_user) {
            return response()->json(['message' => 'Event has reached maximum capacity'], 400);
        }
    
        // Create userInfo record if it does not exist
        UserInfo::firstOrCreate(
            ['user_id' => $validatedData['user_id']], // Search condition
            [ // Data to be created if not found
                'gender' => $validatedData['gender'] ?? 0,
                'mobile_no' => $validatedData['mobile_no'] ?? 0,
                'email' => $validatedData['email'],
                'addr_line_1' => $validatedData['addr_line_1'] ?? 0,
                'addr_line_2' => $validatedData['addr_line_2'] ?? 0,
                'postcode' => $validatedData['postcode'] ?? 0,
                'city' => $validatedData['city'] ?? 0,
                'state' => $validatedData['state'] ?? 0,
                'country' => $validatedData['country'] ?? 0,
                // Add other default values as needed
            ]
        );
    
        // Attach the user to the event with default values for additional fields
        $user->events()->attach($event, [
            'required_transport' => 0,
            'qrcode' => 0,
            'attended' => 0,
            'approved' => 0,
            'mobile_no' => $validatedData['mobile_no'],
            'email' => $validatedData['email'],
            'status' => 0,
            'gender' => $validatedData['gender'],
            'addr_line_1' => $validatedData['addr_line_1'],
            'addr_line_2' => $validatedData['addr_line_2'],
            'postcode' => $validatedData['postcode'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'created_at' => now(),
            'updated_at' => now(),
            // Add other fields as needed
        ]);
    
        return response()->json(['message' => 'User joined the event successfully'], 201);
    }
    
    public function unjoinEvent(Request $request)
    {
        // Define custom validation messages
        $messages = [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'event_id.required' => 'The event ID is required.',
            'event_id.exists' => 'The specified event does not exist.',
        ];
    
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ], $messages);
    
        // Retrieve the user and event
        $user = User::findOrFail($validatedData['user_id']);
        $event = Event::findOrFail($validatedData['event_id']);
    
        // Check if the user is attending the event
        if (!$user->events()->where('event_id', $event->id)->exists()) {
            return response()->json(['message' => 'User is not attending the event'], 400);
        }
    
        // Check if the event is ongoing
        if (now() >= $event->start_datetime) {
            return response()->json(['message' => 'Cannot unjoin an ongoing event'], 400);
        }
 
        // Detach the user from the event
        $user->events()->detach($event);
    
        return response()->json(['message' => 'User unjoined the event successfully'], 200);
    }
    
    public function sendInvitation(Request $request)
    {
        $messages = [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'event_id.required' => 'The event ID is required.',
            'event_id.exists' => 'The specified event does not exist.',
            'type.required' => 'The invitation type is required.',
            'type.in' => 'The invitation type must be either "vip" or "officer".',
        ];
        
        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'type' => 'required|in:vip,officer',
        ], $messages);
        
        $user = User::findOrFail($validatedData['user_id']);
        $event = Event::findOrFail($validatedData['event_id']);
        
        $event = Event::findOrFail($request->event_id);
        $type = $request->input('type');
        $body = '';
        
        if ($type === 'vip') {
            $body = 'You have received a VIP invitation to the event.';
            // Check if the user is already an vip of the event
            $isVIP = $user->vip_events()->where('event_id', $event->id)->exists();
            
            if ($isVIP) {
                return response()->json(['message' => 'User is already a vip of the event'], 400);
            }
        } elseif ($type === 'officer') {
            $body = 'You have received an invitation to be an officer of the event.';
            // Check if the user is already an officer of the event
            $isOfficer = $user->officer_events()->where('event_id', $event->id)->exists();
            
            if ($isOfficer) {
                return response()->json(['message' => 'User is already an officer of the event'], 400);
            }
        } else {
            // Handle other types of invitations
        }
    
        // Check if the user is already invited to the event
        $existingInvitation = Invitation::where('user_id', $validatedData['user_id'])
                                         ->where('event_id', $validatedData['event_id'])
                                         ->exists();
        if ($existingInvitation) {
            return response()->json(['message' => 'User is already invited to the event'], 400);
        }
    
        // Create a new invitation record
        $invitation = Invitation::create([
            'user_id' => $validatedData['user_id'],
            'event_id' => $validatedData['event_id'],
            'type' => $validatedData['type'],
        ]);
    
        // Insert a new notification record
        $notification = Notification::create([
            'event_id' => $request->event_id,
            'user_id' => $invitation->user_id,
            'sender_id' => $event->user_id,
            'type' => 'invitation',
            'title' => 'Invitation',
            'body' => $body,
            // Add any other relevant fields to your notifications table
        ]);
    
        return response()->json(['message' => 'Invitation sent successfully'], 201);
    }
    
    public function becomeOfficer(Request $request)
    {
        // Define custom validation messages
        $customMessages = [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either "accepted" or "rejected".',
        ];
    
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:accepted,rejected', // Validate the status
        ], $customMessages);
        
        $user = User::findOrFail($validatedData['user_id']);
        $event = Event::findOrFail($validatedData['event_id']);
        
        // Check if the user is already an officer of the event
        $isOfficer = $user->officer_events()->where('event_id', $event->id)->exists();
        
        if ($isOfficer) {
            return response()->json(['message' => 'User is already an officer of the event'], 400);
        }
    
        // Find the invitation
        $invitation = Invitation::where('user_id', $validatedData['user_id'])
                                ->where('event_id', $validatedData['event_id'])
                                ->where('type', 'officer')
                                ->first();
    
        // Check if the invitation exists
        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found'], 404);
        }
 
    
        // Handle the user's decision (accept or reject the invitation)
        if ($validatedData['status'] === 'accepted') {
            // Attach the user as an officer of the event
            $user->officer_events()->attach($event, [
                'accepted_at' => now(), // Set the accepted timestamp
                'status' => 'accepted' // Set the status as accepted
                // Add other necessary pivot data
            ]);
    
            // Delete the invitation after acceptance
            $invitation->delete();
    
            return response()->json(['message' => 'User accepted the invitation and became an officer of the event'], 200);
        } elseif ($validatedData['status'] === 'rejected') {
            // Delete the invitation after rejection
            $invitation->delete();
    
            return response()->json(['message' => 'User rejected the invitation to become an officer of the event'], 200);
        } else {
            return response()->json(['message' => 'Invalid status provided'], 400);
        }
    }
    
    public function becomeVIP(Request $request)
    {
        // Define custom validation messages
        $customMessages = [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either "accepted" or "rejected".',
        ];
    
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:accepted,rejected', // Validate the status
        ], $customMessages);
        
        $user = User::findOrFail($validatedData['user_id']);
        $event = Event::findOrFail($validatedData['event_id']);
        
        // Check if the user is already an vip of the event
        $isVIP = $user->vip_events()->where('event_id', $event->id)->exists();
        
        if ($isVIP) {
            return response()->json(['message' => 'User is already a vip of the event'], 400);
        }
    
        // Find the invitation
        $invitation = Invitation::where('user_id', $validatedData['user_id'])
                                ->where('event_id', $validatedData['event_id'])
                                ->where('type', 'vip')
                                ->first();
    
        // Check if the invitation exists
        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found'], 404);
        }
 
    
        // Handle the user's decision (accept or reject the invitation)
        if ($validatedData['status'] === 'accepted') {
            // Attach the user as a vip of the event
            $user->vip_events()->attach($event, [
                'accepted_at' => now(), // Set the accepted timestamp
                'status' => 'accepted' // Set the status as accepted
                // Add other necessary pivot data
            ]);
    
            // Delete the invitation after acceptance
            $invitation->delete();
    
            return response()->json(['message' => 'User accepted the invitation and became a vip of the event'], 200);
        } elseif ($validatedData['status'] === 'rejected') {
            // Delete the invitation after rejection
            $invitation->delete();
    
            return response()->json(['message' => 'User rejected the invitation to become a vip of the event'], 200);
        } else {
            return response()->json(['message' => 'Invalid status provided'], 400);
        }
    }
    
    public function getEventOfficers(Request $request, $eventId)
    {
        $officers = Officer::where('event_id', $eventId)
                       ->join('events', 'officers.event_id', '=', 'events.id')
                       ->join('users', 'officers.user_id', '=', 'users.id')
                       ->where('events.user_id', auth()->id())
                       ->where('officers.status', '=', 'accepted')
                       ->select('officers.*', 'users.name', 'users.email') // Select additional user fields as needed
                       ->get();

        return response()->json(['officers' => $officers], 200);
    }
    
    public function getEventVIPs(Request $request, $eventId)
    {
        $vips = VIP::where('event_id', $eventId)
                       ->join('events', 'vips.event_id', '=', 'events.id')
                       ->join('users', 'vips.user_id', '=', 'users.id')
                       ->where('events.user_id', auth()->id())
                       ->where('vips.status', '=', 'accepted')
                       ->select('vips.*', 'users.name', 'users.email') // Select additional user fields as needed
                       ->get();

        return response()->json(['vips' => $vips], 200);
    }
    
    public function getUserAgencyEvents($userId)
    {
        $user = User::findOrFail($userId);
        $events = $user->agencyEvents()->with('comments')->withCount('likes', 'bookmarks', 'attendees')->get();
    
        return response()->json(['events' => $events], 200);
    }


    public function getAdminEvents($userId)
    {
        $user = User::findOrFail($userId);
        $events = $user->adminEvents()->with('comments')->withCount('likes', 'bookmarks', 'attendees')->get();
    
        return response()->json(['events' => $events], 200);
    }
    
    public function events_approval(Request $request){

        $event_id = $request->input('event_id');
        $event_name = events::where('id', $event_id)->value('title');
        $type = $request->input('answer');
        if ($type == "reject")
        {
            events::where('id', $event_id)->update(['status_id' => '0']);
        }
        else if ($type == "accept")
        {
            events::where('id', $event_id)->update(['status_id' => '1']);
            $this->notify_new_event("",$event_name,$event_id, "New Event");
        }
        return "success";

    }
    
    // public function joinEvent(Request $request, $userId)
    // {
    //     $validatedData = $request->validate([
    //         'event_id' => 'required|string|max:255',
    //         'required_transport' => 'nullable|string|max:255',
    //         'qrcode' => 'nullable|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'attended' => 'nullable|boolean',
    //         'approved' => 'nullable|boolean',
    //         'mobile_no' => 'nullable|string|max:255',
    //         'status' => 'nullable|string|max:255',
    //         'gender' => 'nullable|string|max:255',
    //         'addr_line_1' => 'nullable|string|max:255',
    //         'addr_line_2' => 'nullable|string|max:255',
    //         'postcode' => 'nullable|string|max:255',
    //         'city' => 'nullable|string|max:255',
    //         'state' => 'nullable|string|max:255',
    //         'country' => 'nullable|string|max:255',
    //     ]);
    
    //     // Create or update user info if it doesn't exist
    //     $userInfo = UserInfo::firstOrNew(['user_id' => $userId]);
    //     $userInfo->fill([
    //         'mobile_no' => $validatedData['mobile_no'] ?? null,
    //         'email' => $validatedData['email'],
    //         'addr_line_1' => $validatedData['addr_line_1'] ?? null,
    //         'addr_line_2' => $validatedData['addr_line_2'] ?? null,
    //         'postcode' => $validatedData['postcode'] ?? null,
    //         'city' => $validatedData['city'] ?? null,
    //         'state' => $validatedData['state'] ?? null,
    //         'country' => $validatedData['country'] ?? null,
    //     ]);
    //     $userInfo->save();
    
    //     // Create a new attendee record
    //     $attendee = new Attendee();
    //     $attendee->user_id = $userId;
    //     $attendee->event_id = $validatedData['event_id'];
    //     $attendee->required_transport = $validatedData['required_transport'] ?? null;
    //     $attendee->qrcode = $validatedData['qrcode'] ?? null;
    //     $attendee->email = $validatedData['email'];
    //     $attendee->attended = $validatedData['attended'] ?? null;
    //     $attendee->approved = $validatedData['approved'] ?? null;
    //     $attendee->mobile_no = $validatedData['mobile_no'] ?? null;
    //     $attendee->status = $validatedData['status'] ?? null;
    //     $attendee->gender = $validatedData['gender'] ?? null;
    //     $attendee->addr_line_1 = $validatedData['addr_line_1'] ?? null;
    //     $attendee->addr_line_2 = $validatedData['addr_line_2'] ?? null;
    //     $attendee->postcode = $validatedData['postcode'] ?? null;
    //     $attendee->city = $validatedData['city'] ?? null;
    //     $attendee->state = $validatedData['state'] ?? null;
    //     $attendee->country = $validatedData['country'] ?? null;
    //     $attendee->save();
    
    //     return response()->json(['message' => 'You have successfully joined the event!'], 201);
    // }
    
    
}
