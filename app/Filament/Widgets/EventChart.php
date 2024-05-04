<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Carbon\Carbon;
use App\Models\Event;

class EventChart extends LineChartWidget
{
    protected static ?string $heading = 'Event Chart';
    
    // protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $ongoingEventData = $this->getEventsPerMonth(true);
        $completedEventData = $this->getEventsPerMonth(false);
        
        return [
            'datasets' => [
                [
                    'label' => 'Ongoing Events',
                    'data' => $ongoingEventData['eventsPerMonth'],
                ],
                [
                    'label' => 'Completed Events',
                    'data' => $completedEventData['eventsPerMonth'],
                ],
            ],
            'labels' => $ongoingEventData['months'], // or $completedEventData['months'], as both arrays should have same months
        ];
    }
    
    private function getEventsPerMonth($ongoing = true)
    {
        $now = Carbon::now();
        $eventsPerMonth = [];
        
        $months = collect(range(1, 12))->map(function($month) use ($now, $ongoing){
            $startOfMonth = Carbon::now()->month($month)->startOfMonth();
            $endOfMonth = Carbon::now()->month($month)->endOfMonth();
            
            $query = Event::whereBetween('start_datetime', [$startOfMonth, $endOfMonth]);
            
            if ($ongoing) {
                $query->where('end_datetime', '>=', Carbon::now());
            } else {
                $query->where('end_datetime', '<', Carbon::now());
            }
            
            $count = $query->count(); 
            
            return [
                'count' => $count,
                'month' => $now->month($month)->format('M')
            ];
        })->toArray();
        
        return [
            'eventsPerMonth' => array_column($months, 'count'),
            'months' => array_column($months, 'month')
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasRole("Super Admin");
    }
}

