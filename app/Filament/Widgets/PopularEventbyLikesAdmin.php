<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class PopularEventbyLikesAdmin extends BaseWidget
{
    protected static ?string $heading = 'Popular Event by Likes';
    
    protected static ?int $sort = 5;
    
    protected function getTableQuery(): Builder
    {
        return Event::query()
            ->select('events.id', 'events.title')
            ->selectRaw('COUNT(upvote_downvotes.id) as total_likes')
            ->leftJoin('upvote_downvotes', 'events.id', '=', 'upvote_downvotes.event_id')
            ->where('admin_id', auth()->user()->id)
            ->groupBy('events.id', 'events.title')
            ->orderByDesc(DB::raw('COUNT(upvote_downvotes.id)'));
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Event Title'),
            Tables\Columns\TextColumn::make('total_likes')
                ->label('Total Likes')
                ->sortable(),
            // Add other columns as needed
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole("Admin");
    }
}
