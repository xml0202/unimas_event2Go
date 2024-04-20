<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Comments extends Component
{
    public Event $event;

    protected $listeners = [
        'commentCreated' => '$refresh',
        'commentDeleted' => '$refresh',
    ];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function render()
    {
//        dd('1234');
        $comments = $this->selectComments();
        return view('livewire.comments', compact('comments'));
    }

    /**
     *
     * @return mixed
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    private function selectComments()
    {
        return Comment::where('event_id', '=', $this->event->id)
            ->with(['event', 'user', 'comments'])
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->get();
    }
}
