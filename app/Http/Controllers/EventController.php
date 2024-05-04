<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\UserInfo;
use App\Models\EventView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home(): View
    {
        // Latest event
        $currentTime = now()->setTimezone('Asia/Kuala_Lumpur');
        $oneWeekBefore = Carbon::now()->setTimezone('Asia/Kuala_Lumpur')->subWeek();
        $oneWeekAfter = Carbon::now()->setTimezone('Asia/Kuala_Lumpur')->addWeek();

        $latestEvent = Event::where('status', '=', 1)
            ->where('end_datetime', '>', $currentTime)
            ->orderBy('start_datetime', 'asc')
            ->first();

        // Show the most popular 3 events based on upvotes
        $popularEvents = Event::query()
            ->leftJoin('upvote_downvotes', 'events.id', '=', 'upvote_downvotes.event_id')
            ->select('events.*', DB::raw('COUNT(upvote_downvotes.id) as upvote_count'))
            ->where(function ($query) {
                $query->whereNull('upvote_downvotes.is_upvote')
                    ->orWhere('upvote_downvotes.is_upvote', '=', 1);
            })
            ->where('status', '=', 1)
            ->where('end_datetime', '>', $currentTime)
            ->orderBy('start_datetime', 'asc')
            ->groupBy([
                'events.id',
                'events.title',
                'events.user_id',
                'events.created_at',
                'events.updated_at',
            ])
            ->limit(5)
            ->get();

        // If authorized - Show recommended events based on user upvotes
        $user = auth()->user();

        if ($user) {
            $recommendedEvents = Event::query()
                ->leftJoin('upvote_downvotes', 'events.id', '=', 'upvote_downvotes.event_id')
                ->select('events.*')
                ->where('upvote_downvotes.is_upvote', 1)
                ->where('upvote_downvotes.user_id', $user->id)
                ->where('status', '=', 1)
                ->where('end_datetime', '>', $currentTime)
                ->limit(3)
                ->get();

        } // Not authorized - Popular events based on views
        else {
            $recommendedEvents = Event::query()
                ->leftJoin('event_views', 'events.id', '=', 'event_views.event_id')
                ->select('events.*', DB::raw('COUNT(event_views.id) as view_count'))
                ->where('status', '=', 1)
                ->where('end_datetime', '>', $currentTime)
                ->orderByDesc('view_count')
                ->groupBy([
                    'events.id',
                    'events.title',
                    'events.user_id',
                    'events.created_at',
                    'events.updated_at',
                ])
                ->limit(3)
                ->get();
        }


        // Show recent categories with their latest events
        $categories = Category::query()
//      
            ->select('categories.*')
            ->groupBy([
                'categories.id',
                'categories.category_name',
                'categories.created_at',
                'categories.updated_at',
            ])
            ->limit(5)
            ->get();

         if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
        
            return view('home', compact(
                'latestEvent',
                'popularEvents',
                'recommendedEvents',
                'categories'
            ));
        } elseif (Auth::check()) {
            
            return View('auth.verify-email');
        } else {
            
            return view('home', compact(
                'latestEvent',
                'popularEvents',
                'recommendedEvents',
                'categories'
            ));
        }

        // return view('home', compact(
        //     'latestEvent',
        //     'popularEvents',
        //     'recommendedEvents',
        //     'categories'
        // ));
    }


    /**
     * Display the specified resource.
     */
    public function show(Event $event, Request $request)
    {
        // if (!$event->status || $event->register_start_time > Carbon::now()) {
        //     throw new NotFoundHttpException();
        // }
        
        $currentTime = now()->setTimezone('Asia/Kuala_Lumpur');
        $oneWeekBefore = Carbon::now()->setTimezone('Asia/Kuala_Lumpur')->subWeek();
        $oneWeekAfter = Carbon::now()->setTimezone('Asia/Kuala_Lumpur')->addWeek();
        
        $attendee = null;
        if (auth()->check()) {
            
            $user_id = auth()->user()->id;
            $attendee = UserInfo::query()
            ->where('user_id', $user_id)
            ->limit(1)
            ->first();
            
        $user = $request->user();
        EventView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'event_id' => $event->id,
            'user_id' => $user->id
        ]);
            
        } 
        
        

        $next = Event::query()
            ->where('status', true)
            ->whereDate('start_datetime', '<=', $oneWeekAfter)
            ->whereDate('start_datetime', '>', $event->start_datetime)
            ->orderBy('start_datetime', 'desc')
            ->limit(1)
            ->first();

        $prev = Event::query()
            ->where('status', true)
            ->whereDate('start_datetime', '<=', $oneWeekAfter)
            ->whereDate('start_datetime', '<', $event->start_datetime)
            ->orderBy('start_datetime', 'asc')
            ->limit(1)
            ->first();

        

        return view('event.view', compact('attendee', 'event', 'prev', 'next'));
    }

    public function byCategory(Category $category)
    {
        $currentTime = now()->setTimezone('Asia/Kuala_Lumpur');
        $events = Event::query()
            ->where('category', '=', $category->category_name)
            ->where('status', '=', 1)
            ->where('end_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->paginate(10);

        return view('event.index', compact('events', 'category'));
    }

    public function search(Request $request)
    {
        $currentTime = now()->setTimezone('Asia/Kuala_Lumpur');
        $q = $request->get('q');

        $events = Event::query()
            ->where('status', '=', true)
            ->where('end_datetime', '>=', $currentTime)
            ->orderBy('start_datetime', 'asc')
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('introduction', 'like', "%$q%");
            })
            ->paginate(10);

        return view('event.search', compact('events'));
    }
    
    public function joinEvent(Request $request)
    {
        $price = $request->query('price');
        
        $validatedData = $request->validate([
            'event_id' => 'required|string|max:255',
        ]);
        
        $user = auth()->user();
        
        $existingAttendee = UserInfo::where('mobile_no', $request->input('mobile_no'))->whereNot('user_id', $user->id)->first();
    
        if ($existingAttendee) {
            return response()->json(['message' => 'Mobile number is already in use.'], 422);
        }
        
        
        
        $user->points()->create([
            'action' => 'event_joined',
            'points' => -$price,
        ]);
        
        $user->updateTotalPoints();
        
        if (!$user->userInfo) {
        $userInfo = new UserInfo([
            'user_id' => $user->id,
            'mobile_no' => $request->input('mobile_no'),
            'addr_line_1' => $request->input('addr_line_1'),
            'addr_line_2' => $request->input('addr_line_2'),
            'postcode' => $request->input('postcode'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'gender' => $request->input('gender'),
        ]);
        $userInfo->save();
    }

        $attendee = new Attendee();
        $attendee->user_id = $user->id;
        $attendee->event_id = $validatedData['event_id'];
        $attendee->required_transport = $request->input('required_transport');
        $attendee->qrcode = $request->input('qrcode');
        $attendee->attended = $request->input('attended');
        $attendee->approved = $request->input('approved');
        $attendee->mobile_no = $request->input('mobile_no');
        $attendee->status = $request->input('status');
        $attendee->gender = $request->input('gender');
        $attendee->addr_line_1 = $request->input('addr_line_1');
        $attendee->addr_line_2 = $request->input('addr_line_2');
        $attendee->postcode = $request->input('postcode');
        $attendee->city = $request->input('city');
        $attendee->state = $request->input('state');
        $attendee->country = $request->input('country');
        $attendee->state = $request->input('state');
        
        $attendee->save();

        return redirect()->back()->with('success', 'You have successfully joined the event!');
    }
    
    public function unjoinEvent(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|string|max:255',
        ]);
    
        $user = auth()->user();
        
        $price = $request->query('price');
        
        $user->points()->create([
            'action' => 'event_joined',
            'points' => $price,
        ]);
    
        // Delete the user's attendance record for the specified event
        $user->attendee()->where('event_id', $validatedData['event_id'])->delete();
    
        // Update the user's total points
        $user->updateTotalPoints();
    
        return redirect()->back()->with('success', 'You have successfully unjoined the event!');
    }
}
