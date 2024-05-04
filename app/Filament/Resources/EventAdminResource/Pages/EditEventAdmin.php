<?php

namespace App\Filament\Resources\EventAdminResource\Pages;

use App\Filament\Resources\EventAdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventAdmin extends EditRecord
{
    protected static string $resource = EventAdminResource::class;
    
    protected static ?string $title = 'Event Approval';

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
