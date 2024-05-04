<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static bool $canCreateAnother = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile_no')
                        ->required()
                        ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->options([
                        '1' => 'Male',
                        '0' => 'Female',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('addr_line_1')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('addr_line_2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postcode')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->label('Add Attendees')
                // ->preloadRecordSelect()
                ->modalHeading('Add Attendee')
                ->modalButton('Add')->disableAttachAnother()
                // ->modalSecondaryButton('ok')
                ->successNotificationTitle('Added')
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\Hidden::make('required_transport')->default('0'),
                    Forms\Components\Hidden::make('qrcode')->default('null'),
                    Forms\Components\Hidden::make('attended')->default('0'),
                    Forms\Components\Hidden::make('approved')->default('0'),
                    Forms\Components\Hidden::make('status')->default('1'),
                    Forms\Components\TextInput::make('mobile_no')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->options([
                            '1' => 'Male',
                            '0' => 'Female',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('addr_line_1')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('addr_line_2')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('postcode')
                        ->required()
                        ->maxLength(65535),
                    Forms\Components\TextInput::make('city')
                        ->required()
                        ->maxLength(65535),
                    Forms\Components\TextInput::make('state')
                        ->required()
                        ->maxLength(65535),
                    Forms\Components\TextInput::make('country')
                        ->required()
                        ->maxLength(65535),
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DetachAction::make()->label('Remove')->successNotificationTitle('Removed'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
