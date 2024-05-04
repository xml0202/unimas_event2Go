<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Event;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Ongoing Event', Event::where('end_datetime', '>=', now())
                ->where(function ($query) {
                    if (auth()->user()->hasRole('Admin')) {
                        $query->where('admin_id', auth()->user()->id);
                    } elseif (auth()->user()->hasRole('Agency')) {
                        $query->where('user_id', auth()->user()->id);
                    }
                })
                ->count()),
            Card::make('Completed Event', Event::where('end_datetime', '<=', now())
                ->where(function ($query) {
                    if (auth()->user()->hasRole('Admin')) {
                        $query->where('admin_id', auth()->user()->id);
                    } elseif (auth()->user()->hasRole('Agency')) {
                        $query->where('user_id', auth()->user()->id);
                    }
                })
                ->count()),
            Card::make('Total Users', User::whereHas('roles', function ($query) {
                $query->where('name', 'User');
            })->count()),
        ];
    }
}
