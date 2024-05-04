<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class PopularEventbyAttendeesAdmin extends BaseWidget
{
    protected static ?string $heading = 'Popular Event by Attendees';
    
    protected static ?int $sort = 4;
    
    protected function getTableQuery(): Builder
    {
        return Event::query()
            ->select('events.*')
            ->selectRaw('COUNT(attendees.id) as total_attendees')
            ->leftJoin('attendees', 'events.id', '=', 'attendees.event_id')
            ->where('admin_id', auth()->user()->id)
            ->groupBy('events.id')
            ->orderByDesc(DB::raw('COUNT(attendees.id)'));
            // ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Title'),
            Tables\Columns\TextColumn::make('total_attendees')
                ->label('Total Attendees')
                ->sortable(),
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole("Admin");
    }
}
