<?php

namespace App\Filament\Resources\EventApprovalResource\Pages;

use App\Filament\Resources\EventApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventApproval extends EditRecord
{
    protected static string $resource = EventApprovalResource::class;
    
    protected static ?string $title = 'Event Approval';

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
