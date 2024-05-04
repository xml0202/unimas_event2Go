<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendeeResource\Pages;
use App\Filament\Resources\AttendeeResource\RelationManagers;
use App\Models\Attendee;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendeeResource extends Resource
{
    protected static ?string $model = Attendee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?int $navigationSort = 3;
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('event_id')
                //     ->required(),
                // Forms\Components\TextInput::make('user_id')
                //     ->required(),
                // Forms\Components\Toggle::make('required_transport')
                //     ->required(),
                // Forms\Components\Textarea::make('qrcode')
                //     ->required()
                //     ->maxLength(65535),
                // Forms\Components\Toggle::make('attended')
                //     ->required(),
                // Forms\Components\Toggle::make('approved')
                //     ->required(),
                Forms\Components\TextInput::make('user.name')
                ->label('User Name'),
                Forms\Components\TextInput::make('mobile_no')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                // Forms\Components\Toggle::make('status')
                //     ->required(),
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
                Tables\Columns\TextColumn::make('event.title'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('mobile_no'),
                Tables\Columns\TextColumn::make('user.email'),
                // Tables\Columns\IconColumn::make('status')
                //     ->boolean(),
                Tables\Columns\TextColumn::make('gender')->enum([
                    '1' => 'Male',
                    '0' => 'Female',
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendees::route('/'),
            'create' => Pages\CreateAttendee::route('/create'),
            'edit' => Pages\EditAttendee::route('/{record}/edit'),
        ];
    }    
}
