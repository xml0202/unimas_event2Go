<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationGroup = 'Content';
    
    // public static ?string $pluralModelLabel = 'Member';

    public static function form(Form $form): Form
    {
        $userId = auth()->id();
        
        return $form
            ->schema([
                Forms\Components\Hidden::make('status')->default('1'),
                Forms\Components\TextInput::make('title')->required()->columnSpan(2),
                FileUpload::make('attachment'),
                Forms\Components\Textarea::make('description')->required()->columnSpan(2),
                Forms\Components\Textarea::make('extra_info')->label('Extra Info')->columnSpan(2),
                Forms\Components\DateTimePicker::make('start_time')->label('Start Time')->columnSpan(1)->required(),
                Forms\Components\DateTimePicker::make('end_time')->label('End Time')->columnSpan(1)->required(),
                Forms\Components\DateTimePicker::make('register_start_time')->label('Register Start Time')->columnSpan(1)->required(),
                Forms\Components\DateTimePicker::make('register_end_time')->label('Register End Time')->columnSpan(1)->required(),
                
                Forms\Components\Toggle::make('paid')
                    ->label('Paid')
                    ->reactive()
                    ->requiredWith('price')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $state ? $set('price', null) : $set('price', '0');
                    })
                    ->columnSpan(2)
                    ->required(),
                
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->requiredWith('paid')
                    ->numeric()
                    ->hidden(function (Closure $get): bool {
                        return !$get('paid');
                    })
                    ->columnSpan(1)
                    ->required(),

                Forms\Components\Select::make('category')->label('Category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
                    ->columnSpan(1)->required(),
                Forms\Components\TextInput::make('maxUser')->label('Max Users')->reactive()->columnSpan(1)->required(),
                Forms\Components\TextInput::make('earn_points')->label('Earn Points')->columnSpan(1)->required(),
                Forms\Components\Toggle::make('online')
                    ->reactive()
                    ->requiredWith('location')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $state ? $set('location', null) : $set('location', '');
                    })
                    ->columnSpan(2)
                    ->label('Online')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->requiredWith('online')
                    ->hidden(function (Closure $get): bool {
                        return $get('online');
                    })
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->requiredWith('online')
                    ->hidden(function (Closure $get): bool {
                        return !$get('online');
                    })
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Toggle::make('approval')->label('Approval')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $state ? $set('status', 2) : $set('status', '1');
                    }),
                ]);
                
                
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
    
    public static function getWidgets(): array
    {
        return [
            EventResource\Widgets\EventOverview::class,
        ];
    }

    public static function table(Table $table): Table
    {
        $userId = auth()->id();
        
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\TextColumn::make('register_start_time')->sortable(),
                Tables\Columns\TextColumn::make('register_end_time')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->sortable(),
                Tables\Columns\TextColumn::make('end_time')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Ongoing',
                        '0' => 'Completed',
                        
                    ]),
                SelectFilter::make('category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendeeRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }    
}
