<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\Auth;

class EventReportPage extends Page implements HasForms
{
    use InteractsWithForms;

    public ?Event $event = null;
    public ?array $data = [];
    
    #[Reactive]
    public bool $isEditing = false;

    protected static ?string $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.event-report-page';

    public function mount(Request $request, Event $event): void
    {
        
        if (! Auth::check()) {
            redirect('/admin/login');
        }
        
        $this->event = $event;
        $this->form->fill([
            'report' => $event->report,
        ]);
        $this->isEditing = blank($event->report);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('report')
                ->rows(12)
                ->required()
                ->disabled(fn () => !$this->isEditing),
        ];
    }

    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    public function save(): void
    {
        $data = $this->form->getState();
    
        $this->event->update([
            'report' => $data['report'],
            'report_created_at' => $this->event->report === null ? now() : $this->event->report_created_at,
            'report_updated_at' => now(),
        ]);
    
        $this->isEditing = false;
    
        Notification::make()
            ->title('Report saved successfully!')
            ->success()
            ->send();
    }


    public function delete(): void
    {
        $this->event->update([
            'report' => null,
            'report_created_at' => null,
            'report_updated_at' => null,
        ]);
    
        $this->form->fill(['report' => '']);
        $this->isEditing = true;
    
        Notification::make()
            ->title('Report deleted.')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return '';
    }
    
    public function getBreadcrumbs(): array
    {
        return [
            '/admin/events' => 'Events',
            'Report'
        ];
    }
    
}
