<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    
    // protected function getCreatedNotification(): ?Notification
    // {
    //     activity()->log("Notification sent for category created");
        
    //     return Notification::make()
    //         ->success()
    //         ->title('Category created')
    //         ->body('The category has been created successfully.');
    // }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        // activity()->log("Notification sent for category created");
        return 'Category Created';
    }
}
 

