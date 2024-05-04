<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
