<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\UserInfo;
use App\Models\EventView;
use App\Models\FeedbackAns;
use App\Models\Feedback2Ans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
                'events.admin_id',
                'events.attachment',
                'events.introduction',
                'events.organized_by',
                'events.in_collaboration',
                'events.program_objective',
                'events.program_impact',
                'events.invitation',
                'events.start_datetime',
                'events.end_datetime',
                'events.category',
                'events.location',
                'events.max_user',
                'events.price',
                'events.earn_points',
                'events.comment_enabled',
                'events.event_qr',
                'events.approved',
                'events.approval',
                'events.status',
                'events.Avgrating',
                'events.user_id',
                'events.created_at',
                'events.updated_at',
                'events.url',
                'events.pdf_files',
                'events.points_awarded_at',
                'events.report',
                'events.report_created_at',
                'events.report_updated_at',
                'events.registration_start_datetime',
                'events.registration_close_datetime'
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
                'categories.status',
                'categories.listed',
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
    
    public function generateQRCode($eventId)
    {
        $event = Event::find($eventId);

        // Set expiration time for the QR code (e.g., 5 minutes)
        $expirationTime = now()->addMinutes(5);

        // Generate a unique identifier based on event details and expiration time
        $data = $event->id . "_" . $expirationTime->timestamp;
        
        if ($event) {
            
            $event->event_qr = $data;
      
            $event->save();

        } else {
            // Handle case where event does not exist
        }

        // Generate QR code image
        $qrCode = QrCode::size(200)->generate($data);

        // Store the event ID and expiration time in the session for validation
        session(['qr_code_event_id' => $eventId, 'qr_code_expiration' => $expirationTime]);

        return view('event.qr_code', compact('qrCode', 'event'));
    }
    
    public function feedbackStore(Request $request) {

        $ans = new FeedbackAns();
        $ans->user_id = $request->input('user_id');
        $ans->event_id = $request->input('event_id');
        $ans->q1ans1 = $request->input('Memenuhi');
        $ans->q1ans2 = $request->input('Kesesuaian');
        $ans->q1ans3 = $request->input('diperuntukkan');
        $ans->q1ans4 = $request->input('modul');
        $ans->q2ans1 = $request->input('Pengetahuan');
        $ans->q2ans2 = $request->input('Pembentangan');
        $ans->q2ans3 = $request->input('masa');
        $ans->q2ans4 = $request->input('Interaksi');
        $ans->q2ans5 = $request->input('Persediaan');
        $ans->q3ans1 = $request->input('Pengurusan');
        $ans->q4ans1 = $request->input('masyarakat');
        $ans->q4ans2 = $request->input('manfaat');
        $ans->q4ans3 = $request->input('mencadangkan');
        $ans->q5ans1 = $request->input('Text1');
        $ans->q6ans1 = $request->input('Text2');
        
       // $rating = ($ans->q1ans1 + $ans->q1ans2 + $ans->q1ans3 + $ans->q1ans4 + $ans->q2ans1 + $ans->q2ans2 + $ans->q2ans3 + $ans->q2ans4 + $ans->q2ans5 + ans->q3ans1 + $ans->q4ans1 + $ans->q4ans2 + $ans->q4ans3 ) / 15; 
        
        $rating = ($request->input('Memenuhi') + $request->input('Kesesuaian') + $request->input('diperuntukkan') + $request->input('modul') + $request->input('Pengetahuan') +  $request->input('Pembentangan') + $request->input('masa') + $request->input('Interaksi') + $request->input('Persediaan') + $request->input('Pengurusan') + $request->input('masyarakat') + $request->input('manfaat') + $request->input('mencadangkan')) / 13;
        
        $ans->rating = $rating;
        
        $ans->save();
        
        
        $event_id = $request->input('event_id');
        
        $event = Event::find($event_id);
        
        $test = DB::table('feedback_ans')->where('event_id', $event_id)->avg('rating');
        
        $test1 = DB::table('feedback2_ans')->where('event_id', $event_id)->avg('rating');
        
        $total = ($test +  $test1) / 2;
        
        $event->Avgrating = round($total, 2);
        
        $event->update();

    	return 'Submit Successfully';
    }
    
    public function feedback2Store(Request $request) {

        $ans = new Feedback2Ans();
        $ans->user_id = $request->input('user_id');
        $ans->event_id = $request->input('event_id');
        $ans->q1ans1 = $request->input('Memenuhi');
        $ans->q1ans2 = $request->input('kepuasan');
        $ans->q1ans3 = $request->input('mesra');
        $ans->q1ans4 = $request->input('urusan');
        $ans->q1ans5 = $request->input('dibaiki');
        $ans->q1ans6 = $request->input('manfaat');
        $ans->q2ans1 = $request->input('Pengetahuan');
        $ans->q2ans2 = $request->input('Pembentangan');
        $ans->q2ans3 = $request->input('masa');
        $ans->q3ans1 = $request->input('Text1');
        $ans->q4ans1 = $request->input('Text2');
        
       // $rating = ($ans->q1ans1 + $ans->q1ans2 + $ans->q1ans3 + $ans->q1ans4 + $ans->q2ans1 + $ans->q2ans2 + $ans->q2ans3 + $ans->q2ans4 + $ans->q2ans5 + ans->q3ans1 + $ans->q4ans1 + $ans->q4ans2 + $ans->q4ans3 ) / 15; 
        
        $rating = ($request->input('Memenuhi') + $request->input('kepuasan') +  $request->input('mesra') + $request->input('urusan') + $request->input('dibaiki') + $request->input('manfaat') + $request->input('Pengetahuan') + $request->input('Pembentangan') + $request->input('masa')) / 9;
        
        $ans->rating = $rating;
        
        $ans->save();
        
        $event_id = $request->input('event_id');
        
        $event = Event::find($event_id);
        
        $test = DB::table('feedback_ans')->where('event_id', $event_id)->avg('rating');
        
        $test1 = DB::table('feedback2_ans')->where('event_id', $event_id)->avg('rating');
        
        $total = ($test +  $test1) / 2;
        
        $event->Avgrating = round($total, 2);
        
        $event->update();

    	return 'Submit Successfully';
    }
    
    public function getEventFeedback(Request $request, $event_id, $user_id) {
        $newResponder = FeedbackAns::where('user_id', $user_id)->where('event_id', $event_id)->exists();
        
        $feedback_ans = FeedbackAns::where('user_id', $user_id)->where('event_id', $event_id)->get();
        
        if ($newResponder)
        {
            //return 1;
           return view('api.feedback.feedback_ans', compact('feedback_ans'))->with('user_id',$user_id)->with('event_id', $event_id);
        }
        
        return view('api.feedback.feedback')->with('user_id',$user_id)->with('event_id', $event_id);
        
        //return json_encode($newResponder);
    }
    
    public function getEventFeedback2(Request $request, $event_id, $user_id) {
        $newResponder = Feedback2Ans::where('user_id', $user_id)->where('event_id', $event_id)->exists();
        
        $feedback_ans = Feedback2Ans::where('user_id', $user_id)->where('event_id', $event_id)->get();
        
        if ($newResponder)
        {
            //return 1;
           return view('api.feedback.feedback2_ans', compact('feedback_ans'))->with('user_id',$user_id)->with('event_id', $event_id);
        }
        
        return view('api.feedback.feedback2')->with('user_id',$user_id)->with('event_id', $event_id);
        
        //return json_encode($newResponder);
    }
    
}
