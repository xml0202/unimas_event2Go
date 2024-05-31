<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class SiteController extends Controller
{
    public function bookmarkedEvent(): View
    {
        $currentTime = now()->setTimezone('Asia/Kuala_Lumpur');
        
        $events = Event::query()
            ->select('events.*')
            ->join('bookmarks', 'events.id', '=', 'bookmarks.event_id')
            ->where('bookmarks.user_id', '=', auth()->id()) 
            ->where('events.status', '=', true) 
            ->where('events.end_datetime', '>=', $currentTime)
            ->orderBy('events.start_datetime', 'asc')
            ->paginate(10);

        return view('about', compact('events'));
    }
    
    public function index()
    {
        $user = json_encode(Session::get('user'));
        // dd($user);
        $access_token = Session::get('api_access_token');
        $introspect = json_encode(Session::get('introspect'));


        return view('welcome', compact('user', 'access_token', 'introspect'));
    }
}
