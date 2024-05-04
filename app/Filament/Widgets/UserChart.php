<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Carbon\Carbon;
use App\Models\User;

class UserChart extends LineChartWidget
{
    protected static ?string $heading = 'User Registration Chart';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $userData = $this->getUsersRegisteredPerMonth();
        
        return [
            'datasets' => [
                [
                    'label' => 'Registered Users',
                    'data' => $userData['usersPerMonth'],
                ],
            ],
            'labels' => $userData['months'],
        ];
    }
    
    private function getUsersRegisteredPerMonth()
    {
        $now = Carbon::now();
        $usersPerMonth = [];
        
        $months = collect(range(1, 12))->map(function($month) use ($now){
            $startOfMonth = Carbon::now()->month($month)->startOfMonth();
            $endOfMonth = Carbon::now()->month($month)->endOfMonth();
            
            $count = User::whereHas('roles', function ($query) {
                $query->where('name', 'User');
            })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count(); 
            
            return [
                'count' => $count,
                'month' => $now->month($month)->format('M')
            ];
        })->toArray();
        
        return [
            'usersPerMonth' => array_column($months, 'count'),
            'months' => array_column($months, 'month')
        ];
    }
}

