<?php

namespace App\Filament\Resources\AgencyUserResource\Pages;

use App\Filament\Resources\AgencyUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgencyUser extends CreateRecord
{
    protected static string $resource = AgencyUserResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Agency User Created';
    }
}
