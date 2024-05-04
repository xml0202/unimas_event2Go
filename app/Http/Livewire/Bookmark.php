<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;

class Bookmark extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }
    
    public function render()
    {
        $isBookmark = \App\Models\Bookmark::where('event_id', '=', $this->event->id)
            ->count();
            
        $hasBookmark = null;
        
        $user = request()->user();
        if ($user) {
            $model = \App\Models\Bookmark::where('event_id', '=', $this->event->id)->where('user_id', '=', $user->id)->first();
            if ($model) {
                $hasBookmark = true;
            }
        }

        
        return view('livewire.bookmark', compact('isBookmark', 'hasBookmark'));
    }
    
    public function bookmark()
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        if (!$user) {
            return $this->redirect('login');
        }
        if (!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }

        $model = \App\Models\Bookmark::where('event_id', '=', $this->event->id)->where('user_id', '=', $user->id)->first();

        if (!$model) {
            \App\Models\Bookmark::create([
                'event_id' => $this->event->id,
                'user_id' => $user->id
            ]);

            return;
        }

        else
        {
            $model->delete();
        }
    }
}
