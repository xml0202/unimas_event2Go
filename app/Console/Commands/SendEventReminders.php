<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttendeeEventDay;
use Carbon\Carbon;
use App\Traits\SendsFCMNotification;

class SendEventReminders extends Command
{
    use SendsFCMNotification;

    protected $signature = 'events:send-reminders';
    protected $description = 'Send FCM notifications 1 day before attendee event days';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $eventDays = AttendeeEventDay::with(['attendee.user', 'attendee.event'])
            ->whereDate('event_date', $tomorrow)
            ->get();

        if ($eventDays->isEmpty()) {
            \Log::info("No attendee event days found for {$tomorrow}");
            return;
        }

        foreach ($eventDays as $eventDay) {
            $user = $eventDay->attendee->user ?? null;
            $event = $eventDay->attendee->event ?? null;

            if (!$user || !$event) {
                \Log::warning("Missing user or event for attendee_event_day ID {$eventDay->id}");
                continue;
            }

            // Customize target audience here: ['user'], ['admin'], etc.
            $this->sendNotificationUsingFCMHttpV1(
                ['user'],
                'Event Reminder',
                "Reminder for: " . $event->title,
                $event->id
            );

            \Log::info("Reminder sent to user_id {$user->id} for event_id {$event->id} on {$tomorrow}");
        }

        \Log::info("Reminders sent for all attendee event days on {$tomorrow}");
    }
}
