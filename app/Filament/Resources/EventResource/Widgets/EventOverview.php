<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\EventView;
use App\Models\UpvoteDownvote;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Model;

class EventOverview extends Widget
{
    protected int | string | array $columnSpan = 3;
    
    public ?Model $record = null;
    
    protected function getViewData(): array
    {
        return [
            'viewCount' => EventView::where('event_id', '=', $this->record->id)->count(),
            'like' => UpvoteDownvote::where('event_id', '=', $this->record->id)->where('is_upvote', '=', 1)->count(),
            'bookmark' => Bookmark::where('event_id', '=', $this->record->id)->count(),
        ];
    }
    
    protected static string $view = 'filament.resources.event-resource.widgets.event-overview';
}
