<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendee;
use App\Models\Point;
use DB;

class AssignPointsToUsers extends Command
{
    protected $signature = 'points:assign';
    protected $description = 'Assign points to users after events end.';

    public function handle()
    {
        // Get the current date and time
        $now = now();

        // Retrieve events that have ended, have points to earn, and have not awarded points yet
        $events = Event::where('end_datetime', '<=', $now)
            ->where('earn_points', '>', 0)
            ->whereNull('points_awarded_at')
            ->get();

        foreach ($events as $event) {
            // Get all attendees for this event
            $attendees = Attendee::where('event_id', $event->id)->get();

            foreach ($attendees as $attendee) {
                // Find the user by their ID
                $user = User::find($attendee->user_id);
                
                if ($user) {
                    // Assign points to the user
                    $user->increment('total_points', $event->earn_points);

                    // Optional: Create a point record if needed
                    Point::create([
                        'user_id' => $user->id,
                        'action' => 'Points earned for event: ' . $event->title,
                        'points' => $event->earn_points,
                    ]);
                }
            }

            // Update the points_awarded_at timestamp for this event
            $event->update(['points_awarded_at' => $now]);

            $this->info('Assigned ' . $event->earn_points . ' points to all attendees of event: ' . $event->title);
        }

        return 0; // Indicate success
    }
}


