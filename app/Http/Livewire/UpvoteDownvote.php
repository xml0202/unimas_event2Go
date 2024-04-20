<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;

class UpvoteDownvote extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function render()
    {
        $upvotes = \App\Models\UpvoteDownvote::where('event_id', '=', $this->event->id)
            ->where('is_upvote', true)
            ->count();

        $downvotes = \App\Models\UpvoteDownvote::where('event_id', '=', $this->event->id)
            ->where('is_upvote', false)
            ->count();

        // The status whether current user has upvoted the event or not.
        // This will be null, true, or false
        // null means user has not done upvote or downvote
        $hasUpvote = null;

        /** @var \App\Models\User $user */
        $user = request()->user();
        if ($user) {
            $model = \App\Models\UpvoteDownvote::where('event_id', '=', $this->event->id)->where('user_id', '=', $user->id)->first();
            if ($model) {
                $hasUpvote = !!$model->is_upvote;
            }
        }

        return view('livewire.upvote-downvote', compact('upvotes', 'downvotes', 'hasUpvote'));
    }

    public function upvoteDownvote($upvote = true)
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        if (!$user) {
            return $this->redirect('login');
        }
        if (!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }

        $model = \App\Models\UpvoteDownvote::where('event_id', '=', $this->event->id)->where('user_id', '=', $user->id)->first();

        if (!$model) {
            \App\Models\UpvoteDownvote::create([
                'is_upvote' => $upvote,
                'event_id' => $this->event->id,
                'user_id' => $user->id
            ]);

            return;
        }

        if ($upvote && $model->is_upvote || !$upvote && !$model->is_upvote) {
            $model->delete();
        } else {
            $model->is_upvote = $upvote;
            $model->save();
        }
    }

}
