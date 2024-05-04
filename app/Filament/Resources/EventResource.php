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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;

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
                FileUpload::make('attachment')->multiple()->image(),
                Forms\Components\Textarea::make('introduction')->required()->columnSpan(2),
                Forms\Components\TextInput::make('organized_by')->label('Organized By')->required()->columnSpan(2),
                Forms\Components\TextInput::make('in_collaboration')->label('In Collaboration')->required()->columnSpan(2),
                Forms\Components\TextInput::make('program_objective')->label('Program Objective')->required()->columnSpan(2),
                Forms\Components\TextInput::make('program_impact')->label('Program Impact')->required()->columnSpan(2),
                Forms\Components\TextInput::make('invitation')->label('Invitation')->required()->columnSpan(2),
                Forms\Components\DateTimePicker::make('start_datetime')->label('Start Date')->columnSpan(1)->required(),
                Forms\Components\DateTimePicker::make('end_datetime')->label('End Date')->columnSpan(1)->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->columnSpan(1)
                    ->required(),
                Forms\Components\Select::make('category')->label('Category')
                    ->options(Category::all()->pluck('category_name', 'category_name'))
                    ->columnSpan(1)->required(),
                Forms\Components\TextInput::make('max_user')->label('Max Users')->reactive()->columnSpan(1)->required(),
                Forms\Components\TextInput::make('earn_points')->label('Earn Points')->columnSpan(1)->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->columnSpan(2)
                    ->required(),
                // Forms\Components\Toggle::make('approval')->label('Approval')
                //     ->reactive()
                //     ->afterStateUpdated(function ($state, callable $set) {
                //         $state ? $set('status', 2) : $set('status', '1');
                    // }),
            ]);         
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->when(auth()->user()->hasRole('Super Admin'), function ($query) {
            // Super admins can see all attendees
            return $query;
        }, function ($query) {
            // Regular admins can only see attendees associated with their events
            return $query->where(function ($subQuery) {
                $subQuery->where('admin_id', auth()->id())
                    ->orWhere('user_id', auth()->id());
            });
        });
            
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
                Tables\Columns\TextColumn::make('start_datetime')->date()->sortable(),
                Tables\Columns\TextColumn::make('end_datetime')->date()->sortable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendeesRelationManager::class,
        ];
    }
    
    public function isReadOnly(): bool
    {
        return true;
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
