<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgencyUser;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User Created';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email_verified_at'] = Carbon::now();
        $data['password'] = bcrypt($data['password']);
        // if (blank($data['roles'])) {
        //     $data['roles'] = 'Agency';
        // }
        // $data['roles'] = 'Agency';
        // if (auth()->user()->hasRole('Super Admin'))
        // {
        //     $data['roles'] = ['Agency'];
        // }
        return $data;
    }
    

    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\User $user */
        $user = parent::handleRecordCreation($data);
        // if (auth()->user()->hasRole('Super Admin'))
        // {
        //     $user->assignRole('Agency');
        // }
        // $user->assignRole('admin');
        
        // $anotherModelData = [
        //     'admin_id' => 1, 
        //     'agency_id' => 1, 
        //     'user_id' => 1,
        //     'status' => 1,
        // ];
    
        // AgencyUser::create($anotherModelData);

        return $user;
    }

}
