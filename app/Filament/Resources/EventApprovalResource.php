<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventApprovalResource\Pages;
use App\Filament\Resources\EventApprovalResource\RelationManagers;
use App\Models\EventApproval;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;

class EventApprovalResource extends Resource
{
    protected static ?string $model = EventApproval::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    public static ?string $pluralModelLabel = 'Events Approval';
    
    protected static ?string $navigationGroup = 'Inbox';
    
    protected static ?int $navigationGroupSort = 1;
    
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $userId = auth()->id();
        
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->disabled(true)->columnSpan(2),
                FileUpload::make('attachment')->disabled(true),
                Forms\Components\Textarea::make('description')->disabled(true)->columnSpan(2),
                Forms\Components\Textarea::make('extra_info')->label('Extra Info')->disabled(true)->columnSpan(2),
                Forms\Components\DateTimePicker::make('start_time')->label('Start Time')->columnSpan(1)->disabled(true),
                Forms\Components\DateTimePicker::make('end_time')->label('End Time')->columnSpan(1)->disabled(true),
                Forms\Components\DateTimePicker::make('register_start_time')->label('Register Start Time')->columnSpan(1)->disabled(true),
                Forms\Components\DateTimePicker::make('register_end_time')->label('Register End Time')->columnSpan(1)->disabled(true),
                
                Forms\Components\Toggle::make('paid')
                    ->label('Paid')
                    ->reactive()
                    ->requiredWith('price')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $state ? $set('price', null) : $set('price', '0');
                    })
                    ->columnSpan(2)
                    ->disabled(true),
                
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->requiredWith('paid')
                    ->numeric()
                    ->hidden(function (Closure $get): bool {
                        return !$get('paid');
                    })
                    ->columnSpan(1)
                    ->disabled(true),

                Forms\Components\Select::make('category')->label('Category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
                    ->columnSpan(1)->disabled(true),
                Forms\Components\TextInput::make('maxUser')->label('Max Users')->reactive()->columnSpan(1)->disabled(true),
                Forms\Components\TextInput::make('earn_points')->label('Earn Points')->columnSpan(1)->disabled(true),
                Forms\Components\Toggle::make('online')
                    ->reactive()
                    ->requiredWith('location')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $state ? $set('location', null) : $set('location', '');
                    })
                    ->columnSpan(2)
                    ->label('Online')
                    ->disabled(true),
                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->requiredWith('online')
                    ->hidden(function (Closure $get): bool {
                        return $get('online');
                    })
                    ->columnSpan(2)
                    ->disabled(true),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->requiredWith('online')
                    ->hidden(function (Closure $get): bool {
                        return !$get('online');
                    })
                    ->columnSpan(2)
                    ->disabled(true),
                    Select::make('status')
                        ->options([
                            '1' => 'Approve',
                            '0' => 'Reject',
                        ])
                
                ]);
    }

    public static function table(Table $table): Table
    {
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Approval'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEventApprovals::route('/'),
            // 'create' => Pages\CreateEventApproval::route('/create'),
            'edit' => Pages\EditEventApproval::route('/{record}/edit'),
        ];
    } 
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 2)->where('admin_id', auth()->id());
    }
}
