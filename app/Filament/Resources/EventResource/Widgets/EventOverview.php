<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\EventView;
use App\Models\UpvoteDownvote;
use Illuminate\Database\Eloquent\Model;

class EventOverview extends Widget
{
    protected int | string | array $columnSpan = 3;
    
    public ?Model $record = null;
    
    protected function getViewData(): array
    {
        return [
            'viewCount' => EventView::where('event_id', '=', $this->record->id)->count(),
            'upvotes' => UpvoteDownvote::where('event_id', '=', $this->record->id)->where('is_upvote', '=', 1)->count(),
            'downvotes' => UpvoteDownvote::where('event_id', '=', $this->record->id)->where('is_upvote', '=', 0)->count(),
        ];
    }
    
    protected static string $view = 'filament.resources.event-resource.widgets.event-overview';
}
