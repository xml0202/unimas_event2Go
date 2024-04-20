<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Event;
use Livewire\Component;

class CommentCreate extends Component
{
    public string $comment = '';

    public Event $event;

    public ?Comment $commentModel = null;
    public ?Comment $parentComment = null;

    public function mount(Event $event, $commentModel = null, $parentComment = null)
    {
        $this->event = $event;
        $this->commentModel = $commentModel;
        $this->comment = $commentModel ? $commentModel->comment : '';

        $this->parentComment = $parentComment;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function createComment()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        if ($this->commentModel) {
            if ($this->commentModel->user_id != $user->id) {
                return response('You are not allowed to perform this action', 403);
            }

            $this->commentModel->comment = $this->comment;
            $this->commentModel->save();

            $this->comment = '';
            $this->emitUp('commentUpdated');
        } else {
            $comment = Comment::create([
                'comment' => $this->comment,
                'event_id' => $this->event->id,
                'user_id' => $user->id,
                'parent_id' => $this->parentComment?->id
            ]);

            $this->emitUp('commentCreated', $comment->id);
            $this->comment = '';
        }
    }
}
