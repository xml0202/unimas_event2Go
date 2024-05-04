<?php

namespace App\Filament\Resources\AgencyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use App\Models\AgencyUser;
use Illuminate\Support\Str;
use Closure;

class AgencyUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'AgencyUsers';

    protected static ?string $recordTitleAttribute = 'user_id';

    public static function form(Form $form): Form
    {
        $userId = auth()->id();
        
        $userOptions = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->whereNotIn('id', AgencyUser::pluck('user_id'))
        ->where('role_id', 3)
        ->pluck('name', 'id');
        
        return $form
            ->schema([
                Hidden::make('admin_id')->default($userId),
                Hidden::make('status')->default(1),
                // Radio::make('type')
                //     ->label('Type')
                //     ->options([
                //         'existing' => 'Existing',
                //         'new' => 'New',
                //     ])->inline()->required()->reactive()->columnSpan(2),
                Select::make('user_id')
                    ->label('Name')
                    ->options($userOptions)
                    ->searchable()
                    ->required()
                    // ->visible(fn() => auth()->user()->hasRole('Super Admin')), 
                    // ->visible(function (Closure $get): string {
                    //     return $get('type') === 'existing';
                    // }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
