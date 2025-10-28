<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Attendee;
use App\Models\TeamAttendee;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserInfo;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\AgencyUser;
use App\Models\Officer;
use App\Models\VIP;
use App\Models\feedback;
use App\Models\FeedbackList;
use App\Models\FeedbackAns;
use App\Models\Feedback2Ans;
use App\Models\Attendance;
use App\Models\PointSetup;
use App\Models\Point;
use App\Models\PlacementHistory;
use App\Models\EventView;
use App\Models\AttendeeEventDay;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use DB;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['attendees', 'views'])
            ->with(['officers' => function ($query) {
                $query->select('users.id', 'users.name', 'profiles.picture', 'profiles.phoneNo', 'profiles.email')
                      ->join('profiles', 'users.id', '=', 'profiles.user_id')
                      ->wherePivot('status', 'accepted');
            }])
            ->where(function ($query) {
                $query->whereNull('end_datetime')
                      ->orWhere('end_datetime', '>=', now());
            })
            ->get();
    
        $eventList = $events->map(function ($event) {
            $eventData = $event->toArray();
    
            $eventData['officers'] = $event->officers->map(function ($officer) {
                return [
                    'id' => $officer->id,
                    'name' => $officer->name,
                    'email' => $officer->email,
                    'phone_no' => $officer->phoneNo,
                    'profile_picture' => $officer->picture ?? null,
                ];
            });
    
            return $eventData;
        });
    
        return response()->json(['data' => $eventList]);
    }


    
    public function uploadPdf(Request $request, $eventId)
    {
        $event = Event::find($eventId);
    
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'pdf_files'   => 'required|array',
            'pdf_files.*' => 'file|mimes:pdf|max:10240',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
    
        // Delete old files
        if (!empty($event->pdf_files)) {
            foreach ($event->pdf_files as $oldFile) {
                Storage::delete('public/' . $oldFile);
            }
        }
    
        // Upload new files
        $newFiles = [];
        foreach ($request->file('pdf_files') as $file) {
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/events', $fileName);
            
            // Store only the relative path in the database
            $newFiles[] = str_replace('public/', '', $path);
        }
    
        // Update event record
        $event->update(['pdf_files' => $newFiles]);
    
        return response()->json([
            'message' => 'PDFs replaced successfully',
            'pdfs'    => array_map(fn($file) => asset("storage/$file"), $newFiles)
        ]);
    }

    
    public function getPdfs(Event $event)
    {
        if (empty($event->pdf_files)) {
            return response()->json(['message' => 'No PDFs available'], 404);
        }
    
        return response()->json([
            'pdfs' => array_map(fn($file) => asset($file), $event->pdf_files)
        ]);
    }

    
    public function pendingApproval()
    {
        $pendingEvents = Event::where('status', 2)->get();
    
        return response()->json(['pending_events' => EventResource::collection($pendingEvents)], 200);
    }
    
    public function approveOrReject(Request $request, $eventId)
    {
        // Validate the request
        $validatedData = $request->validate([
            'approved' => 'required|boolean', // true for approval, false for rejection
        ]);
    
        // Find the event
        $event = Event::findOrFail($eventId);
        
        $users = User::role('User')->whereNotNull('fcm_token')->get();
    
        if ($event->status != 2) {
            return response()->json(['message' => 'Event is not pending approval'], 400);
        }
    
        // Update the status based on approval or rejection
        if ($validatedData['approved']) {
            $event->status = 1; // Approved
            $this->sendNotificationUsingFCMHttpV1(['user'], "New Event", $event->title, $event->id);
            
            foreach ($users as $user) {
                Notification::create([
                    'event_id'  => $event->id,               
                    'user_id'   => $user->id,              
                    'sender_id' => "1",
                    'type'      => "New Event",           
                    'title'     => $event->title,
                    'body'      => $event->introduction
                ]);
            }
            
        } else {
            $event->status = 3; // Rejected
        }
    
        $event->save();
    
        return response()->json(['message' => 'Event status updated successfully', 'event' => new EventResource($event)], 200);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'attachment' => 'required|array',
            'attachment.*' => 'required|string',
            'url' => 'nullable|string',
            'introduction' => 'required|string',
            'organized_by' => 'required|string',
            'in_collaboration' => 'nullable|string',
            'program_objective' => 'required|string',
            'program_impact' => 'required|string',
            'invitation' => 'nullable|string',
    
            // Conditional date fields
            'registration_start_datetime' => [
                Rule::requiredIf(fn () => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date',
            ],
            'registration_close_datetime' => [
                Rule::requiredIf(fn () => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date', 'after:registration_start_datetime',
            ],
            'start_datetime' => [
                Rule::requiredIf(fn () => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date',
            ],
            'end_datetime' => [
                Rule::requiredIf(fn () => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date', 'after:start_datetime',
            ],
    
            'category' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Category::where('category_name', $value)->exists()) {
                        $fail('The selected category is invalid.');
                    }
                },
            ],
            'location' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'max_user' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'earn_points' => 'nullable|integer|min:0',
            'approved' => 'required|boolean',
            'approval' => 'nullable|string',
            'comment_enabled' => 'required|boolean',
            'pdf_files' => 'nullable|array',
            'pdf_files.*' => 'required|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_email' => 'nullable|email|max:255',
            'pic_contact' => 'nullable|string|max:50'
        ]);
    
        /**
         * Handle image attachments
         */
        $attachments = [];
        foreach ($validatedData['attachment'] as $attachmentData) {
            $fileContent = base64_decode($attachmentData);
            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $fileContent, FILEINFO_MIME_TYPE);
            finfo_close($finfo);
    
            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => 'jpg',
            };
    
            $fileName = Str::random(20) . '.' . $extension;
            Storage::disk('public')->put($fileName, $fileContent);
            $attachments[] = $fileName;
        }
    
        $validatedData['attachment'] = $attachments;
    
        /**
         * Handle optional document files
         */
        $docFiles = [];
        if (!empty($validatedData['pdf_files'])) {
            foreach ($validatedData['pdf_files'] as $docData) {
                $fileContent = base64_decode($docData);
                $finfo = finfo_open();
                $mimeType = finfo_buffer($finfo, $fileContent, FILEINFO_MIME_TYPE);
                finfo_close($finfo);
    
                $extension = match ($mimeType) {
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                    'application/vnd.ms-powerpoint' => 'ppt',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
                    default => 'bin',
                };
    
                $fileName = Str::random(20) . '.' . $extension;
                Storage::disk('public')->put($fileName, $fileContent);
                $docFiles[] = $fileName;
            }
    
            $validatedData['pdf_files'] = $docFiles;
        }
    
        // ðŸ”¹ Get the authenticated user ID from token
        $userId = auth()->id();
    
        // ðŸ”¹ Add admin_id and status
        $adminId = AgencyUser::where('user_id', $userId)->value('admin_id');
        $validatedData['admin_id'] = $adminId;
        $validatedData['user_id'] = $userId;
        $validatedData['status'] = $request->approval ? 2 : 1;
    
        // ðŸ”¹ Create the event
        $event = Event::create($validatedData);
    
        // ðŸ”¹ Send notifications
        if ($validatedData['status'] == 1) {
            $this->sendNotificationUsingFCMHttpV1(['user'], "New Event", $validatedData['title'], $event->id);
    
            $users = User::role('User')->whereNotNull('fcm_token')->get();
            foreach ($users as $user) {
                Notification::create([
                    'event_id'  => $event->id,
                    'user_id'   => $user->id,
                    'sender_id' => $userId,
                    'type'      => "New Event",
                    'title'     => $event->title,
                    'body'      => $event->introduction,
                ]);
            }
        } else {
            $this->sendNotificationUsingFCMHttpV1(['admin'], "New Event Approval", $validatedData['title'], $event->id, $adminId);
        }
    
        return response()->json([
            'message' => 'Event created successfully',
            'event'   => new EventResource($event),
        ], 201);
    }




    public function show(Request $request, $id)
    {
        try {
            $userId = $request->user()?->id;
    
            $event = Event::with([
                'comments.user:id,name',
                'comments.user.profile:user_id,picture'
            ])
            ->withCount(['likes', 'bookmarks', 'attendees'])
            ->findOrFail($id);
    
            $officers = User::select('users.id', 'users.name', 'profiles.picture', 'profiles.phoneNo', 'profiles.email')
                ->join('officers', 'users.id', '=', 'officers.user_id')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->where('officers.event_id', $id)
                ->where('officers.status', 'accepted')
                ->get();
    
            $liked = $event->userLike($userId)->exists() ? 1 : 0;
            $bookmarked = $event->userBookmark($userId)->exists() ? 1 : 0;
            $joined = $event->userAttendee($userId)->exists() ? 1 : 0;
            $team_joined = $event->teamAttendees()->where(function ($query) use ($userId) {
                $query->where('team_member_1', $userId)
                      ->orWhere('team_member_2', $userId)
                      ->orWhere('team_member_3', $userId)
                      ->orWhere('team_member_4', $userId)
                      ->orWhere('team_member_5', $userId);
            })->exists() ? 1 : 0;
    
            $eventArray = $event->toArray();
    
            foreach ($eventArray['comments'] as &$comment) {
                $comment['profile_picture'] = $comment['user']['profile']['picture'];
                $comment['name'] = $comment['user']['name'];
                unset($comment['user']);
            }
    
            $officersArray = $officers->map(fn ($officer) => [
                'id' => $officer->id,
                'name' => $officer->name,
                'email' => $officer->email,
                'phone_no' => $officer->phoneNo,
                'profile_picture' => $officer->picture,
            ]);
    
            return response()->json([
                'data' => $eventArray,
                'user_liked' => $liked,
                'user_bookmarked' => $bookmarked,
                'user_joined' => $joined,
                'user_team_joined' => $team_joined,
                'total_attendees' => $event->attendees()->count(),
                'total_views' => $event->views()->count(),
                'officers' => $officersArray,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    }



    
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $authUser = $request->user(); // Automatically resolved from Bearer token
    
        // Validate request (no more user_id)
        $validatedData = $request->validate([
            'title' => 'nullable|string',
            'attachment' => 'nullable|array',
            'attachment.*' => 'nullable|string',
            'pdf_files' => 'nullable|array',
            'pdf_files.*' => 'nullable|string',
            'url' => 'nullable|string',
            'introduction' => 'nullable|string',
            'organized_by' => 'nullable|string',
            'in_collaboration' => 'nullable|string',
            'program_objective' => 'nullable|string',
            'program_impact' => 'nullable|string',
            'invitation' => 'nullable|string',
    
            'registration_start_datetime' => [
                Rule::requiredIf(fn() => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date',
            ],
            'registration_close_datetime' => [
                Rule::requiredIf(fn() => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date', 'after:registration_start_datetime',
            ],
            'start_datetime' => [
                Rule::requiredIf(fn() => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date',
            ],
            'end_datetime' => [
                Rule::requiredIf(fn() => !in_array($request->category, ['Ad', 'Explorer'])),
                'nullable', 'date', 'after:start_datetime',
            ],
    
            'category' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && !Category::where('category_name', $value)->exists()) {
                        $fail('The selected category is invalid.');
                    }
                },
            ],
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'max_user' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'earn_points' => 'nullable|integer|min:0',
            'approved' => 'nullable|boolean',
            'approval' => 'nullable|string',
            'comment_enabled' => 'nullable|boolean',
            'pic_name' => 'nullable|string|max:255',
            'pic_email' => 'nullable|email|max:255',
            'pic_contact' => 'nullable|string|max:50',
        ]);
    
        /**
         * Handle image attachments
         */
        if (!empty($validatedData['attachment'])) {
            $attachments = [];
            foreach ($validatedData['attachment'] as $attachmentData) {
                $fileContent = base64_decode($attachmentData);
                $mimeType = finfo_buffer(finfo_open(), $fileContent, FILEINFO_MIME_TYPE);
                $extension = match ($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    default => 'jpg',
                };
                $fileName = Str::random(20) . '.' . $extension;
                Storage::disk('public')->put($fileName, $fileContent);
                $attachments[] = $fileName;
            }
            $validatedData['attachment'] = $attachments;
        }
    
        /**
         * Handle PDF / Office documents
         */
        if (!empty($validatedData['pdf_files'])) {
            $docFiles = [];
            foreach ($validatedData['pdf_files'] as $docData) {
                $fileContent = base64_decode($docData);
                $mimeType = finfo_buffer(finfo_open(), $fileContent, FILEINFO_MIME_TYPE);
                $extension = match ($mimeType) {
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                    'application/vnd.ms-powerpoint' => 'ppt',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
                    default => 'bin',
                };
                $fileName = Str::random(20) . '.' . $extension;
                Storage::disk('public')->put($fileName, $fileContent);
                $docFiles[] = $fileName;
            }
            $validatedData['pdf_files'] = $docFiles;
        }
    
        // Use the authenticated user to find their admin ID
        $validatedData['admin_id'] = AgencyUser::where('user_id', $authUser->id)->value('admin_id');
    
        // Handle approval status
        if ($request->has('approval')) {
            $validatedData['status'] = $request->approval ? 2 : 1;
        }
    
        $event->update($validatedData);
    
        return response()->json([
            'message' => 'Event updated successfully',
            'event'   => new EventResource($event),
        ], 200);
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
    
    public function getUserBookmarkedEvents($user_id)
    {
        $perPage = 10;
    
        $events = Event::join('bookmarks', 'events.id', '=', 'bookmarks.event_id')
            ->where('bookmarks.user_id', '=', $user_id)
            ->select('events.*')
            ->paginate($perPage);
    
        return response()->json($events, 200);
    }

    
    public function getEventsbyCategory($category_id)
    {
        $events = Event::join('categories', 'events.category', '=', 'categories.category_name')
            ->where(function ($query) {
                $query->whereNull('events.end_datetime')
                      ->orWhere('events.end_datetime', '>=', now());
            })
            ->where('events.status', 1)
            ->where('categories.id', $category_id)
            ->select('events.*', 'categories.category_name as category_name')
            ->get();
    
        return response()->json(['events' => $events], 200);
    }

    
    public function getOngoingEvents()
    {
        $ongoingEvents = Event::where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->where('status', 1)
            ->get();

        return response()->json(['ongoing_events' => $ongoingEvents], 200);
    }
    
    public function getOngoingEventsByYear($year, $month)
    {
        $ongoingEvents = Event::whereYear('start_datetime', $year)
            ->whereMonth('start_datetime', $month)
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
    
    public function getUpcomingEventsByYear($year, $month)
    {
        $upcomingEvents = Event::whereYear('start_datetime', $year)
            ->whereMonth('start_datetime', $month)
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
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $today = now()->toDateString();
    
        $attendees = \App\Models\Attendee::with([
            'event', 
            'eventDays' => function ($query) use ($today) {
                $query->where('event_date', '<', $today);
            }
        ])
        ->where('user_id', $userId)
        ->whereHas('eventDays', function ($query) use ($today) {
            $query->where('event_date', '<', $today);
        })
        ->get();
    
        $result = [];
    
        foreach ($attendees as $attendee) {
            $eventData = $attendee->event ? $attendee->event->toArray() : [];
    
            foreach ($attendee->eventDays as $eventDay) {
                $result[] = array_merge($eventData, [
                    'event_day'   => $eventDay->event_date,
                    'attendee_id'=> $attendee->id,
                    'user_id'    => $userId,
                ]);
            }
        }
    
        return response()->json([
            'user_id' => $userId,
            'past_event_days' => $result
        ]);
    }




    
    public function getUserJoinedEvents($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $today = now()->toDateString();
    
        // Load attendees + event + eventDays
        $attendances = Attendee::where('user_id', $userId)
            ->whereHas('event', function ($query) {
                $query->where('end_datetime', '>=', now());
            })
            ->with(['event', 'eventDays' => function ($q) use ($today) {
                $q->where('event_date', '>=', $today);
            }])
            ->get();
        
        $flattened = [];
    
        foreach ($attendances as $attendee) {
            $event = $attendee->event;
    
            foreach ($attendee->eventDays as $eventDay) {
                $flattened[] = array_merge(
                    [
                        'event_day_id' => $eventDay->id,
                        'event_date' => $eventDay->event_date,
                        'check_in_time' => $eventDay->check_in_time,
                        'check_out_time' => $eventDay->check_out_time,
                    ],
                    $event->toArray() // full event columns
                );
            }
        }
    
        return response()->json([
            'user_id' => $userId,
            'joined_events' => $flattened
        ]);
    }
    
    public function getEventAttendees($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        // Get future event dates (linked via AttendeeEventDay)
        $eventDates = AttendeeEventDay::whereHas('attendee', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            
            ->select('event_date')
            ->distinct()
            ->orderBy('event_date')
            ->pluck('event_date');
    
        $result = [];
    
        foreach ($eventDates as $date) {
            // Get attendee IDs for this date
            $attendeeIds = AttendeeEventDay::whereDate('event_date', $date)
                ->whereHas('attendee', function ($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                })
                ->pluck('attendee_id');
    
            // Get attendees with user and profile info
            $attendees = Attendee::whereIn('attendees.id', $attendeeIds)
                ->leftJoin('profiles', 'attendees.user_id', '=', 'profiles.user_id')
                ->leftJoin('users', 'attendees.user_id', '=', 'users.id')
                ->select(
                    'attendees.*',
                    'users.username',
                    'users.email',
                    'users.otp',
                    'users.otp_expiry',
                    'users.email_verified_at',
                    'users.created_at as user_created_at',
                    'users.updated_at as user_updated_at',
                    'users.total_points',
                    'profiles.fullname as name',
                    'profiles.picture as profile_picture'
                )
                ->get();
    
            // Format
            $formatted = $attendees->map(function ($a) {
                return [
                    'id' => $a->user_id,
                    'username' => $a->username ?? '',
                    'name' => $a->name ?? '',
                    'email' => $a->email ?? '',
                    'otp' => $a->otp,
                    'otp_expiry' => $a->otp_expiry,
                    'email_verified_at' => $a->email_verified_at,
                    'created_at' => $a->user_created_at,
                    'updated_at' => $a->user_updated_at,
                    'total_points' => $a->total_points ?? '0',
                    'profile_picture' => $a->profile_picture,
                    'pivot' => [
                        'event_id' => $a->event_id,
                        'user_id' => $a->user_id,
                        'required_transport' => $a->required_transport,
                        'qrcode' => $a->qrcode,
                        'attended' => $a->attended,
                        'approved' => $a->approved,
                        'mobile_no' => $a->mobile_no,
                        'status' => $a->status,
                        'gender' => $a->gender,
                        'addr_line_1' => $a->addr_line_1,
                        'addr_line_2' => $a->addr_line_2,
                        'postcode' => $a->postcode,
                        'city' => $a->city,
                        'state' => $a->state,
                        'country' => $a->country,
                        'created_at' => $a->created_at,
                        'updated_at' => $a->updated_at,
                    ]
                ];
            });
    
            $result[] = [
                'event_date' => $date,
                'attendees' => $formatted,
            ];
        }
    
        return response()->json([
            'event_id' => $eventId,
            'grouped_attendees' => $result
        ]);
    }


    public function getAllUsersPoints()
    {
        // Retrieve all users with their points
        $usersPoints = User::select('users.id', 'users.name', 'users.total_points', 'profiles.picture')
    ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
    ->get();


        return response()->json(['users_points' => $usersPoints], 200);
    }
    
    public function getUserPoints($userId)
    {
        // Retrieve the user
        $user = User::select('users.id', 'users.name', 'users.total_points', 'profiles.picture')
                ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                ->where('users.id', $userId)
                ->first();

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
        $highestPointsUsers = User::select('users.*', 'profiles.picture')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->orderBy('users.total_points', 'desc')
            ->take(10)
            ->get();

        return response()->json(['highest_points_users' => $highestPointsUsers], 200);
    }
    
    public function getUserInfo($userId)
    {
        // Retrieve the user info for the specified user ID, including the profile
        $userInfo = UserInfo::where('user_id', $userId)->with('profile')->first();
    
        if (!$userInfo) {
            return response()->json(['message' => 'User info not found'], 404);
        }
    
        // Extract the profile picture if available
        $profile_picture = $userInfo->profile->picture ?? null;
    
        // Add the profile picture to the user info response
        $userInfo->profile_picture = $profile_picture;
    
        // Optionally, remove the profile object if you don't need it in the response
        unset($userInfo->profile);
    
        return response()->json(['user_info' => $userInfo], 200);
    }



    public function joinEvent(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'event_date' => 'required|date',
            'gender' => 'required',
            'mobile_no' => 'required',
            'email' => 'required|email',
            'addr_line_1' => 'required',
            'addr_line_2' => 'nullable',
            'postcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'type' => 'required',
        ]);
    
        $event = Event::findOrFail($validatedData['event_id']);
        $eventDate = Carbon::parse($validatedData['event_date']);
    
        if (
            $eventDate->lt(Carbon::parse($event->start_datetime)->startOfDay()) ||
            $eventDate->gt(Carbon::parse($event->end_datetime)->endOfDay())
        ) {
            return response()->json(['message' => 'Invalid event date selected'], 400);
        }
    
        $now = now();
        if (
            $now->lt(Carbon::parse($event->registration_start_datetime)) ||
            $now->gt(Carbon::parse($event->registration_end_datetime))
        ) {
            return response()->json(['message' => 'Registration is closed'], 400);
        }
    
        $existing = AttendeeEventDay::where('event_date', $eventDate->toDateString())
            ->whereHas('attendee', function ($q) use ($user, $event) {
                $q->where('user_id', $user->id)
                  ->where('event_id', $event->id);
            })->first();
    
        if ($existing) {
            return response()->json(['message' => 'Already registered for this event date'], 400);
        }
    
        $attendee = Attendee::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->latest()
            ->first();
    
        $fieldsToCompare = [
            'gender', 'mobile_no', 'email', 'addr_line_1', 'addr_line_2',
            'postcode', 'city', 'state', 'country', 'type'
        ];
    
        $needsNewAttendee = false;
    
        if ($attendee) {
            foreach ($fieldsToCompare as $field) {
                if ($attendee->$field != $validatedData[$field]) {
                    $needsNewAttendee = true;
                    break;
                }
            }
        }
    
        if (!$attendee || $needsNewAttendee) {
            try {
                $attendee = Attendee::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'required_transport' => 0,
                    'qrcode' => 0,
                    'attended' => 0,
                    'approved' => 0,
                    'mobile_no' => $validatedData['mobile_no'],
                    'email' => $validatedData['email'],
                    'status' => 0,
                    'gender' => $validatedData['gender'],
                    'addr_line_1' => $validatedData['addr_line_1'],
                    'addr_line_2' => $validatedData['addr_line_2'] ?? '',
                    'postcode' => $validatedData['postcode'],
                    'city' => $validatedData['city'],
                    'state' => $validatedData['state'],
                    'country' => $validatedData['country'],
                    'type' => $validatedData['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create attendee: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to create attendee'], 500);
            }
        }
    
        try {
            AttendeeEventDay::create([
                'attendee_id' => $attendee->id,
                'event_date' => $eventDate->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create AttendeeEventDay: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to register for event day'], 500);
        }
    
        return response()->json([
            'message' => 'Successfully registered for event day',
            'attendee_id' => $attendee->id,
            'event_date' => $eventDate->toDateString(),
        ]);
    }


    public function unjoinEvent(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'event_date' => 'required|date',
        ]);
    
        $event = Event::findOrFail($validatedData['event_id']);
        $eventDate = Carbon::parse($validatedData['event_date']);
    
        
        if (
            $eventDate->lt(Carbon::parse($event->start_datetime)->startOfDay()) ||
            $eventDate->gt(Carbon::parse($event->end_datetime)->endOfDay())
        ) {
            return response()->json(['message' => 'Invalid event date selected'], 400);
        }
    
        if ($eventDate->isToday() || $eventDate->isPast()) {
            return response()->json(['message' => 'Cannot unjoin from ongoing or past event date'], 400);
        }
    
        $attendee = Attendee::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->latest()
            ->first();
    
        if (!$attendee) {
            return response()->json(['message' => 'User is not registered for this event'], 400);
        }
    
        $eventDay = AttendeeEventDay::where('attendee_id', $attendee->id)
            ->where('event_date', $eventDate->toDateString())
            ->first();
    
        if (!$eventDay) {
            return response()->json(['message' => 'User is not registered for the selected event date'], 400);
        }
    
        try {
            $eventDay->delete();
            return response()->json(['message' => 'Successfully unjoined from the event date'], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to unjoin event day: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to unjoin event day'], 500);
        }
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
            else {
                $user->officer_events()->attach($event, [
                    'status' => 'pending' 
                ]);
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
        $existingOfficer = $user->officer_events()
                            ->where('event_id', $event->id)
                            ->wherePivot('status', 'accepted')
                            ->first();
        
        if ($existingOfficer) {
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
            $user->officer_events()->updateExistingPivot($event->id, [
                'accepted_at' => now(), // Set the accepted timestamp
                'status' => 'accepted' // Set the status as accepted
            ]);
    
            // Delete the invitation after acceptance
            $invitation->delete();
    
            return response()->json(['message' => 'User accepted the invitation and became an officer of the event'], 200);
        } elseif ($validatedData['status'] === 'rejected') {
            
            $user->officer_events()->detach($event->id);
            // Delete the invitation after rejection
            $invitation->delete();
    
            return response()->json(['message' => 'User rejected the invitation to become an officer of the event'], 200);
        } else {
            return response()->json(['message' => 'Invalid status provided'], 400);
        }
    }
    
    public function remove_officer(Request $request)
    {
        $officer_id = $request->input('officer_id');
        $event_id = $request->input('event_id');
        // $agency_id = $request->input('user_id');
    
        // Find the event
        $event = Event::find($event_id);

        // Check if the event exists
        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => "Event not found",
            ]);
        }
        
        $isOfficer = $event->officer_users()->wherePivot('user_id', $officer_id)->exists();

        if (!$isOfficer) {
            return response()->json([
                'status' => 404,
                'message' => "Officer is not assigned to the event",
            ]);
        }
    
        // Detach the officer from the event
        $event->officer_users()->detach($officer_id);
    
        // Optionally, generate a notification if needed
        // $this->notify->generate_notification($officer_id, $agency_id, 'remove_event_officer', $event_id, "");
    
        return response()->json([
            'status' => 200,
            'message' => "Officer removed successfully",
        ]);
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
        $events = Event::where('user_id', $userId)->with('comments')->withCount('likes', 'bookmarks', 'attendees')->get();
    
        return response()->json(['events' => $events], 200);
    }
    
    public function getUserAgencyOngoingEvents($userId)
    {
        $now = Carbon::now();
    
        // Fetch ongoing events for the user
        $ongoingEvents = Event::where('user_id', $userId)
            ->with('comments')
            ->withCount('likes', 'bookmarks', 'attendees')
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->get();
    
        return response()->json(['ongoing_events' => $ongoingEvents], 200);
    }

    public function getUserAgencyCompletedEvents($userId)
    {
        $now = Carbon::now();
    
        $completedEvents = Event::where('user_id', $userId)
            ->with('comments')
            ->withCount('likes', 'bookmarks', 'attendees')
            ->where('end_datetime', '<', $now)
            ->get();
    
        return response()->json(['completed_events' => $completedEvents], 200);
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
    
    
    public function getFeedbackList(Request $request)
    {
        //$event_id = $request->input('event_id');
        
        $feedback_list = FeedbackList::all();
        
        return compact('feedback_list');
    }
    
    public function attendanceEvent(Request $request)
    {
        $eventId = $request->input('event_id');
        $tag = $request->input('tag');
        $qrEventId = $request->input('qr_event');
    
        // Prefer authenticated user over input
        $userId = $request->user()->id ?? $request->input('user_id');
    
        if (!$userId || !$eventId) {
            return response()->json(['status' => "400", 'msg' => 'Missing required parameters']);
        }
    
        // Validate event existence first
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['status' => "404", 'msg' => 'Event not found']);
        }
    
        // ✅ Validate the QR code matches current event_qr in DB
        if ($tag !== 'manual' && $qrEventId !== $event->event_qr) {
            return response()->json(['status' => "409", 'msg' => 'Invalid or expired QR code']);
        }
    
        // Validate time window
        if (!$this->is_time_valid($event->start_datetime, $event->end_datetime)) {
            return response()->json(['status' => "409", 'msg' => 'Attendance is out of the available period']);
        }
    
        // Validate attendee record
        $attendee = Attendee::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();
    
        if (!$attendee) {
            return response()->json(['status' => "409", 'msg' => 'Record not found']);
        }
    
        // Check duplicate attendance
        if ($attendee->attended) {
            return response()->json(['success' => false, 'msg' => 'Attendance has already been recorded']);
        }
    
        // Record attendance
        Attendance::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'check_in_date' => now()->toDateString(),
        ]);
    
        $attendee->update(['attended' => true]);
    
        $totalAttendees = Attendee::where('event_id', $eventId)->where('approved', 1)->count();
        $attendedCount = Attendee::where('event_id', $eventId)->where('attended', 1)->count();
    
        // If manual mode, return pending attendees
        if ($tag === 'manual') {
            $pendingAttendees = Attendee::with('user')
                ->where('event_id', $eventId)
                ->where('attended', 0)
                ->get();
    
            return response()->json(['attendees' => $pendingAttendees]);
        }
    
        return response()->json([
            'status' => "200",
            'data' => [
                'user' => $request->user() ?? User::find($userId),
                'attendee' => $attendee,
                'totalAttendees' => $totalAttendees,
                'attendedCount' => $attendedCount
            ]
        ]);
    }



    private function is_time_valid($startTime, $endTime)
    {
        $now = Carbon::now();
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        return $now->between($start, $end);
    }
    
    public function getAttendanceByDate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'date' => 'required|date',
        ]);

        // Fetch the event_id and date from the request
        $eventId = $request->input('event_id');
        $date = $request->input('date');

        // Query to get attendance records for the specific event on the specified date
        $attendances = Attendance::where('event_id', $eventId)
            ->whereDate('check_in_date', $date)
            ->with(['user', 'event'])  // Optional: eager load user and event relationships
            ->get();

        // Return the attendance records in a JSON response
        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }
    
    public function getEventAttendanceDetails(Request $request) {
        if($request->has('event_id')) {
            $attendees = Attendee::where('event_id', $request->get('event_id'))->count();
            $attended = Attendee::where('attended', 1)->where('event_id', $request->get('event_id'))->count();
            return array('success' => true, 'data' => compact('attendees', 'attended'));
            return 1;
        } else {

            //return null;
            return response()->json([
                    'status' => 'null',
                    ]);
        }
    }

    public function search_user(Request $request)
    {
        $email = $request->input('email');
        
        if ($email) {
            $users = User::where('email', $email)->get();
            
            if ($users->isEmpty()) {
                return response()->json(['data' => 'Not Exists']);
            }
    
            return response()->json(['data' => $users]);
        }
    
        return response()->json(['status' => '400', 'msg' => 'Email is required']);
    }

    public function listOfficer(Request $request)
    {
        $agency_user_id = $request->input('user_id'); // The agency user ID

        // Fetch events created by the agency user
        $events = Event::where('user_id', $agency_user_id)->get();
    
        // Initialize an array to hold all officers
        $officers = collect();
    
        // Iterate over each event and get its officers
        foreach ($events as $event) {
            $event_officers = $event->officer_users()->wherePivot('status', 'accepted')->get();
            $officers = $officers->merge($event_officers);
        }
    
        // Remove duplicate officers (if any)
        $officers = $officers->unique('id');
    
        return response()->json([
            'officers' => $officers,
        ]);
    }
    
    public function getOfficerEvents(Request $request)
    {
        $officer_id = $request->input('officer_id'); // The officer ID
    
        // Fetch events where this officer is assigned with the "accepted" status
        $events = Event::whereHas('officer_users', function ($query) use ($officer_id) {
            $query->where('user_id', $officer_id)
                  ->where('status', 'accepted');
        })->get();
    
        return response()->json([
            'events' => $events,
        ]);
    }

    public function listPendingOfficer(Request $request)
    {
        $agency_user_id = $request->input('user_id'); // The agency user ID

        // Fetch events created by the agency user
        $events = Event::where('user_id', $agency_user_id)->get();
    
        // Initialize an array to hold all officers
        $officers = collect();
    
        // Iterate over each event and get its officers
        foreach ($events as $event) {
            $event_officers = $event->officer_users()->wherePivot('status', 'pending')->get();
            $officers = $officers->merge($event_officers);
        }
    
        // Remove duplicate officers (if any)
        $officers = $officers->unique('id');
    
        return response()->json([
            'officers' => $officers,
        ]);
    }
    
    public function register_event_team(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'event_id' => 'required|integer',
            'event_date' => 'required|date',
            'team_name' => 'required|string',
            'team_leader' => 'required|string|email',
            'team_member_1' => 'nullable|string|email',
            'team_member_2' => 'nullable|string|email',
            'team_member_3' => 'nullable|string|email',
            'team_member_4' => 'nullable|string|email',
            'team_member_5' => 'nullable|string|email',
        ]);
    
        // Fetch event
        $event = Event::find($validatedData['event_id']);
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The selected event does not exist.',
                'short_msg' => 'Event not found.'
            ]);
        }
    
        // Validate event_date is within event period
        $eventDate = Carbon::parse($validatedData['event_date']);
        $startDate = Carbon::parse($event->start_datetime)->startOfDay();
        $endDate = Carbon::parse($event->end_datetime)->endOfDay();
    
        if (!$eventDate->between($startDate, $endDate)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The selected event date (' . $eventDate->toDateString() . ') is outside the event period.',
                'short_msg' => 'Invalid event date.'
            ]);
        }
    
    
        // Get team leader's user ID
        $team_leader_id = User::where('email', $validatedData['team_leader'])->value('id');
        if (!$team_leader_id) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The team leader "' . $validatedData['team_leader'] . '" does not exist.',
                'short_msg' => 'Team leader not found.'
            ]);
        }
    
        // Ensure team leader is not already registered
        $is_team_leader_registered = Attendee::where('user_id', $team_leader_id)
            ->where('event_id', $validatedData['event_id'])
            ->where('type', "Team")
            ->exists();
    
        if ($is_team_leader_registered) {
            return response()->json([
                'status' => 'conflict',
                'msg' => 'The team leader "' . $validatedData['team_leader'] . '" has already joined this event.',
                'short_msg' => 'Team leader already registered.'
            ]);
        }
    
        if ($this->hasUserJoinedEventOnDate($team_leader_id, $validatedData['event_id'], $validatedData['event_date'])) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The team leader has already joined the event on ' . $validatedData['event_date'] . '.',
                'short_msg' => 'Leader already joined.'
            ]);
        }
        
        // Check if team name is already taken
        $existingTeam = TeamAttendee::where('event_id', $validatedData['event_id'])
            ->where('team_name', $validatedData['team_name'])
            ->exists();
    
        if ($existingTeam) {
            return response()->json([
                'status' => 'conflict',
                'msg' => 'A team with the name "' . $validatedData['team_name'] . '" already exists for this event.',
                'short_msg' => 'Team name already taken.'
            ]);
        }
    
        // Validate and check attendance of members
        $members = ['team_member_1', 'team_member_2', 'team_member_3', 'team_member_4', 'team_member_5'];
        $member_ids = [];
    
        foreach ($members as $member) {
            if (!empty($validatedData[$member])) {
                $user_id = User::where('email', $validatedData[$member])->value('id');
                if (!$user_id) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => 'The team member "' . $validatedData[$member] . '" does not exist.',
                        'short_msg' => 'Member not found.'
                    ]);
                }
    
                $is_member_registered = Attendee::where('user_id', $user_id)
                    ->where('event_id', $validatedData['event_id'])
                    ->where('type', 'Team')
                    ->exists();
    
                if ($is_member_registered) {
                    return response()->json([
                        'status' => 'conflict',
                        'msg' => 'The team member "' . $validatedData[$member] . '" has already joined this event.',
                        'short_msg' => 'Member already registered.'
                    ]);
                }
    
                if ($this->hasUserJoinedEventOnDate($user_id, $validatedData['event_id'], $validatedData['event_date'])) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => 'The team member "' . $validatedData[$member] . '" has already joined the event on ' . $validatedData['event_date'] . '.',
                        'short_msg' => 'Member already joined.'
                    ]);
                }

    
                $member_ids[$member] = $user_id;
            }
        }
    
        // Create team
        $attendees_team = TeamAttendee::create([
            'event_id' => $validatedData['event_id'],
            'team_name' => $validatedData['team_name'],
            'event_date' => $validatedData['event_date'],
            'team_leader' => $team_leader_id,
            'team_member_1' => $member_ids['team_member_1'] ?? null,
            'team_member_2' => $member_ids['team_member_2'] ?? null,
            'team_member_3' => $member_ids['team_member_3'] ?? null,
            'team_member_4' => $member_ids['team_member_4'] ?? null,
            'team_member_5' => $member_ids['team_member_5'] ?? null,
        ]);
        
    
        $this->sendNotificationsToTeamMembers($attendees_team, $team_leader_id, $validatedData['event_id'], $validatedData['event_date']);
    
        return response()->json([
            'status' => 'success',
            'msg' => 'The team "' . $validatedData['team_name'] . '" has been successfully registered for the event.',
            'short_msg' => 'Team registered successfully.'
        ]);
    }

    protected function hasUserJoinedEventOnDate($userId, $eventId, $eventDate)
    {
        return AttendeeEventDay::whereHas('attendee', function ($query) use ($userId, $eventId) {
            $query->where('user_id', $userId)
                  ->where('event_id', $eventId);
        })->whereDate('event_date', $eventDate)->exists();
    }



    protected function sendNotificationsToTeamMembers($team, $teamLeaderId, $eventId, $eventDate)
    {
        $eventTitle = Event::where('id', $eventId)->value('title') ?? 'an event';
    
        $teamMembers = array_filter([
            $team->team_member_1,
            $team->team_member_2,
            $team->team_member_3,
            $team->team_member_4,
            $team->team_member_5,
        ]);

    
        // Exclude the team leader if somehow included
        $teamMembers = array_diff($teamMembers, [$teamLeaderId]);
    
        foreach ($teamMembers as $memberId) {
            Notification::create([
                'event_id'   => $eventId,
                'event_date' => $eventDate,
                'user_id'    => $memberId,
                'sender_id'  => $teamLeaderId,
                'title'      => 'New Team Registered',
                'body'       => 'You have been added to the team "' . $team->team_name . '" for event "' . $eventTitle . '" on ' . $eventDate . '.',
                'read_at'    => null,
                'type'       => 'team_registration',
            ]);
        }
    }

    
    public function unregister_event_team(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|integer',
            'event_date' => 'required|date',
            'team_name' => 'required|string',
            'team_leader' => 'required|string|email',
        ]);
    
        $eventId = $validatedData['event_id'];
        $eventDate = $validatedData['event_date'];
        
        $teamLeaderId = User::where('email', $validatedData['team_leader'])->value('id');
    
        if (!$teamLeaderId) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The team leader with email "' . $validatedData['team_leader'] . '" does not exist.',
                'short_msg' => 'User not found.',
            ]);
        }
    
        $team = TeamAttendee::where('event_id', $eventId)
            ->where('team_name', $validatedData['team_name'])
            ->where('team_leader', $teamLeaderId)
            ->whereDate('event_date', $eventDate)
            ->first();
    
        if (!$team) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The team "' . $validatedData['team_name'] . '" is not registered for this event on the specified date.',
                'short_msg' => 'Team not found.',
            ]);
        }
    
        // Remove event day for team leader
        $attendeeLeader = Attendee::where('user_id', $teamLeaderId)
            ->where('event_id', $eventId)
            ->first();
    
        if ($attendeeLeader) {
            AttendeeEventDay::where('attendee_id', $attendeeLeader->id)
                ->whereDate('event_date', $eventDate)
                ->delete();
    
            // Optionally delete Attendee if no other days remain
            if (!$attendeeLeader->eventDays()->exists()) {
                $attendeeLeader->delete();
            }
        }
    
        // Remove event day for members
        $members = $team->getMembers();
        foreach ($members as $memberId) {
            if ($memberId) {
                $attendeeMember = Attendee::where('user_id', $memberId)
                    ->where('event_id', $eventId)
                    ->where('type', "Team")
                    ->first();
    
                if ($attendeeMember) {
                    AttendeeEventDay::where('attendee_id', $attendeeMember->id)
                        ->whereDate('event_date', $eventDate)
                        ->delete();
    
                    if (!$attendeeMember->eventDays()->exists()) {
                        $attendeeMember->delete();
                    }
                }
            }
        }
    
        // Delete the team for that specific date
        $team->delete();
    
        // Remove related notifications
        Notification::where('event_id', $eventId)
            ->where('event_date', $eventDate)
            ->where(function ($q) use ($members, $teamLeaderId) {
                $q->whereIn('user_id', $members)
                  ->orWhere('user_id', $teamLeaderId);
            })
            ->delete();
    
        return response()->json([
            'status' => 'success',
            'msg' => 'The team "' . $validatedData['team_name'] . '" has been unregistered from the event on ' . $eventDate . '.',
            'short_msg' => 'Team unregistered.',
        ]);
    }

 

    public function getEventPoint(Request $request)
    {
        $event_point = PointSetup::all();
        
        return response()->json(compact('event_point'));  
    }
    
    public function assign_points(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'placement_id' => 'required|integer',
            'participant_id' => 'required|integer', // This can be either user ID or team ID
            'is_team' => 'required|boolean', // true if participant_id is a team ID, false if user ID
            'event_id' => 'required|integer', // Include event_id to link to the placement history
        ]);
    
        // Retrieve points associated with the placement
        $placement = PointSetup::find($validatedData['placement_id']);
        if (!$placement) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error: Invalid placement ID.',
                'short_msg' => 'Invalid placement.',
            ]);
        }
    
        // Check if placement history already exists for this placement, event, and type (team or individual)
        $existingPlacement = PlacementHistory::where('event_id', $validatedData['event_id'])
            ->where('placement_id', $validatedData['placement_id'])
            ->where('is_team', $validatedData['is_team'])
            ->first();
    
        if ($existingPlacement) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error: This placement already exists for this event and type.',
                'short_msg' => 'Duplicate placement.',
            ]);
        }
    
        // Check if placement history already exists for the participant in the event
        $existingHistory = PlacementHistory::where('event_id', $validatedData['event_id'])
            ->where('participant_id', $validatedData['participant_id'])
            ->where('is_team', $validatedData['is_team'])
            ->first();
    
        if ($existingHistory) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error: Placement history already exists for this participant in this event.',
                'short_msg' => 'Duplicate placement history.',
            ]);
        }
    
        // Check if participant is a team or individual and assign points accordingly
        if ($validatedData['is_team']) {
            // Fetch team members if it's a team
            $team = TeamAttendee::find($validatedData['participant_id']);
            if (!$team) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Error: Team not found.',
                    'short_msg' => 'Invalid team.',
                ]);
            }
    
            // Include the team leader in the points assignment
            $memberIds = array_filter([$team->team_leader, $team->team_member_1, $team->team_member_2, $team->team_member_3, $team->team_member_4, $team->team_member_5]);
    
            // Loop through each member and insert points if they havenâ€™t already been assigned
            foreach ($memberIds as $member_id) {
                if ($member_id) { // Ensure there's a member ID
                    $existingPoint = Point::where('user_id', $member_id)
                        ->where('action', 'Team Placement - ' . $placement->name)
                        ->first();
    
                    if (!$existingPoint) {
                        // Insert points for each team member
                        Point::create([
                            'user_id' => $member_id,
                            'action' => 'Team Placement - ' . $placement->name,
                            'points' => $placement->points,
                        ]);
    
                        $this->updateTotalPoints($member_id);
                    }
                }
            }
    
            // Record placement history for the team
            PlacementHistory::create([
                'event_id' => $validatedData['event_id'],
                'participant_id' => $validatedData['participant_id'], // Use team_id here
                'is_team' => true,
                'placement_id' => $validatedData['placement_id'],
                'points_awarded' => $placement->points,
            ]);
        } else {
            // Insert points for the individual if not already assigned
            $existingPoint = Point::where('user_id', $validatedData['participant_id'])
                ->where('action', 'Solo Placement - ' . $placement->name)
                ->first();
    
            if (!$existingPoint) {
                Point::create([
                    'user_id' => $validatedData['participant_id'],
                    'action' => 'Solo Placement - ' . $placement->name,
                    'points' => $placement->points,
                ]);
    
                $this->updateTotalPoints($validatedData['participant_id']);
            }
    
            // Record placement history for the individual user
            PlacementHistory::create([
                'event_id' => $validatedData['event_id'],
                'participant_id' => $validatedData['participant_id'], // Use user_id here
                'is_team' => false,
                'placement_id' => $validatedData['placement_id'],
                'points_awarded' => $placement->points,
            ]);
        }
    
        return response()->json([
            'status' => 'success',
            'msg' => 'Success: Points have been assigned successfully.',
            'short_msg' => 'Points assigned.',
        ]);
    }
    

     
    public function getEventPlacementSummary($event_id)
    {
        // Validate the event ID
        $event = Event::find($event_id);
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error: Event not found.',
                'short_msg' => 'Invalid event.',
            ]);
        }
    
        // Fetch placements for the event
        $placements = PlacementHistory::where('event_id', $event_id)->get();
    
        // Prepare the summary data
        $summary = [];
        foreach ($placements as $placement) {
            if ($placement->is_team) {
                // Fetch team details and list of members with user info
                $team = TeamAttendee::find($placement->participant_id);
                $teamMembers = collect([
                    $team->team_leader,
                    $team->team_member_1,
                    $team->team_member_2,
                    $team->team_member_3,
                    $team->team_member_4,
                    $team->team_member_5,
                ])->filter(); // Filter out null values
    
                // Fetch user details for each team member
                $memberDetails = $teamMembers->map(function ($member_id) {
                    $user = User::find($member_id);
                    return $user ? [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ] : null;
                })->filter(); // Filter out null values if any user not found
    
                $summary[] = [
                    'type' => 'team',
                    'team_name' => $team->team_name ?? 'N/A',
                    'team_id' => $placement->participant_id,
                    'placement_id' => $placement->placement_id,
                    'points_awarded' => $placement->points_awarded,
                    'placement_name' => PointSetup::find($placement->placement_id)->name ?? 'N/A',
                    'members' => $memberDetails->values()->all(),
                ];
            } else {
                // Fetch individual participant details
                $user = User::find($placement->participant_id);
                $summary[] = [
                    'type' => 'individual',
                    'participant' => $user ? [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ] : 'N/A',
                    'placement_id' => $placement->placement_id,
                    'points_awarded' => $placement->points_awarded,
                    'placement_name' => PointSetup::find($placement->placement_id)->name ?? 'N/A',
                ];
            }
        }
    
        return response()->json([
            'status' => 'success',
            'msg' => 'Success: Event placement summary retrieved.',
            'short_msg' => 'Placement summary retrieved.',
            'data' => [
                'event_id' => $event_id,
                'placements' => $summary,
            ],
        ]);
    }
    
    public function getEventTeams($eventId)
    {
        // Find the event by ID
        $event = Event::find($eventId);
    
        // Check if the event exists
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Event not found.',
            ], 404);
        }
    
        // Get the teams associated with the event
        $teams = $event->teams; // Assuming you have a relationship defined in the Event model
    
        // Prepare the response with user information
        $teamsWithUsers = $teams->map(function ($team) {
            // Fetch user details for team leader
            $teamLeader = User::find($team->team_leader);
            $teamMembers = [
                User::find($team->team_member_1),
                User::find($team->team_member_2),
                User::find($team->team_member_3),
                User::find($team->team_member_4),
                User::find($team->team_member_5),
            ];
    
            // Filter out null values (if any member not found)
            $teamMembers = array_filter($teamMembers);
    
            return [
                'id' => $team->id,
                'event_id' => $team->event_id,
                'team_name' => $team->team_name,
                'date' => $team->event_date,
                'team_leader' => $teamLeader ? [
                    'user_id' => $teamLeader->id,
                    'name' => $teamLeader->name,
                    'email' => $teamLeader->email,
                ] : null,
                'team_members' => array_map(function ($member) {
                    return $member ? [
                        'user_id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                    ] : null;
                }, $teamMembers),
                'created_at' => \Carbon\Carbon::parse($team->created_at)->format('Y-m-d H:i:s'), // Format the created_at date
                'updated_at' => \Carbon\Carbon::parse($team->updated_at)->format('Y-m-d H:i:s'), // Format the updated_at date
            ];
        });
    
        return response()->json([
            'status' => 'success',
            'teams' => $teamsWithUsers,
        ]);
    }


    private function updateTotalPoints($userId)
    {
        // Calculate the sum of points for the user
        $totalPoints = Point::where('user_id', $userId)->sum('points');
    
        // Update the total_point in the users table
        User::where('id', $userId)->update(['total_points' => $totalPoints]);
    }

    
    public function getEventAttendanceDate(Request $request)
    {
        // Get the 'event_id' from the request
        $eventId = $request->input('event_id'); // Or $request->get('event_id')
    
        // Query for distinct dates for the given event_id
        $distinctDates = Attendance::select(DB::raw('DATE(check_in_date) as date'))
            ->where('event_id', $eventId)  // Use the event_id from the request
            ->orderBy('date', 'ASC')
            ->distinct()
            ->get();
    
        // Optionally, return the distinct dates as a JSON response
        return response()->json($distinctDates);
    }
    
    public function get_event_result(Request $request)
    {
        
        $event_id = $request->input('event_id');
        // $type = Event::where('id', $event_id)->value('type');
        //return $type;
        // if ($type == "Team")
        // {
        //     $result_list = DB::select( DB::raw("SELECT c.team_name, b.name FROM event_user_point a
        //         INNER JOIN event_point_setup b ON a.point = b.point
        //         INNER JOIN event_attendees_team c ON c.id = a.user_id
        //         WHERE a.event_id = '$event_id'") );
        //         return compact('result_list');
        // }
        // else if ($type == "Solo")
        // {
        //     $result_list = DB::select( DB::raw("SELECT d.name as username, d.fullname, b.name FROM event_user_point a
        //         INNER JOIN event_point_setup b ON a.point = b.point
        //         INNER JOIN event_attendees_team c ON c.id = a.user_id
        //         INNER JOIN users d ON d.id = c.team_leader
        //         WHERE a.event_id = '$event_id'") );
        //         return compact('result_list');
        // }
        
        
        $result_list = DB::select( DB::raw("SELECT DISTINCT e.name, d.name as title FROM events a
                INNER JOIN attendees b ON b.event_id = a.id
                INNER JOIN points c ON c.user_id = b.user_id
                INNER JOIN point_setups d ON d.points = c.points
                INNER JOIN users e ON e.id = b.user_id
                WHERE a.id = '$event_id'") );
                return compact('result_list');

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
    
    public function sendNotificationUsingFCMHttpV1(array $roles, $title, $body, $eventId, $adminId = null)
    {
        $projectId = 'event2go-1329c'; 
    
        // Log the input parameters for debugging
        \Log::info('Sending Notification', [
            'roles' => $roles,
            'title' => $title,
            'body' => $body,
            'event_id' => $eventId,
            'admin_id' => $adminId,
        ]);
    
        $query = User::role($roles) 
            ->whereNotNull('fcm_token'); 
    
        if ($adminId) {
            $query = User::role($roles) 
            ->join('events', 'events.admin_id', '=', 'users.id')
            ->whereNotNull('users.fcm_token')
            ->where('events.id', $eventId)
            ->where('events.admin_id', $adminId);
        }
        
    
        // Retrieve FCM tokens
        $tokens = $query->pluck('fcm_token')->toArray();
    
        // Log retrieved tokens
        \Log::info('Filtered FCM Tokens:', ['tokens' => $tokens]);
    
        // If no tokens found, return an error
        if (empty($tokens)) {
            return response()->json([
                'message' => "No FCM tokens found for event ID: $eventId and roles: " . implode(', ', $roles),
            ], 404);
        }
    
        // Prepare notification message
        $message = [
            'message' => [
                'notification' => [
                    'title' => $title, // Custom title
                    'body' => $body,   // Custom body
                ],
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                ],
            ],
        ];
    
        // Fetch Access Token
        $accessToken = $this->fetchAccessToken();
    
        // Send Notifications
        foreach ($tokens as $token) {
            $message['message']['token'] = $token;
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);
    
            // Log response details
            if (!$response->successful()) {
                \Log::error("Failed to send FCM notification to token: $token. Error: " . $response->body());
            } else {
                \Log::info("Successfully sent FCM notification to token: $token. Response: " . $response->body());
            }
        }
    
        // Final response
        return response()->json(['message' => 'Notifications sent successfully.']);
    }



    
    public function fetchAccessToken()
    {
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
        
        if (!file_exists($credentialsPath)) {
            Log::error("Service account credentials file does not exist at: " . $credentialsPath);
            return null;
        }
    
        try {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging']; 
    
            $credentials = new ServiceAccountCredentials(
                $scopes,
                $credentialsPath
            );
        
            $token = $credentials->fetchAuthToken();
        
            if (isset($token['access_token'])) {
                return $token['access_token'];
            } else {
                Log::error("Failed to fetch access token", ['token' => $token]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error fetching access token", [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    public function increaseView(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
    
        $user = $request->user(); // From Bearer token (sanctum/passport/etc)
    
        $view = EventView::create([
            'event_id'   => $validated['event_id'],
            'user_id'    => $user?->id, // Automatically null if not logged in
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    
        return response()->json([
            'message' => 'Event view recorded.',
            'data'    => $view,
        ], 201);
    }


    public function storeFcmToken(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Get authenticated user
        $user = auth()->user();
    
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
    
        try {
            
            $user->update([
                'fcm_token' => $request->fcm_token,
            ]);
    
            return response()->json([
                'message' => 'FCM token saved successfully.',
                'fcm_token' => $user->fcm_token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save FCM token.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
}
