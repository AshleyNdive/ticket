<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use Filament\Actions\Action;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

     protected function getKanbanStatusColumnName(): string
    {
        return 'status';
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                Select::make('project_id')
                    ->relationship('project', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('priority_id')
                    ->relationship('priority', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                //  Many-to-Many Select (Assignees)
                Select::make('assignees')
                    ->relationship('assignees', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Select one or more users.'),

                //  Hidden Creator Field
                Hidden::make('created_by')
                    ->default(fn () => Auth::id())
                    ->required()
                    ->dehydrated(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),


                TextColumn::make('project.title')->sortable(),


                BadgeColumn::make('status.name')
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'Open' => 'danger',
                        'In Progress' => 'warning',
                        'Closed' => 'success',
                        default => 'secondary',
                    }),

                TextColumn::make('priority.name')->sortable(),
                TextColumn::make('creator.name')->label('Created By')->sortable(),

                // Column to list Many-to-Many Relationships
                TextColumn::make('assignees.name')
                    ->label('Assigned Users')
                    ->listWithLineBreaks()
                    ->bulleted(),
            ])
            ->filters([
                
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'title')
                    ->label('Project')
                    ->preload(),

                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship('status', 'name')
                    ->label('Status')
                    ->preload(),

                Tables\Filters\SelectFilter::make('priority_id')
                    ->relationship('priority', 'name')
                    ->label('Priority')
                    ->preload(),

                Tables\Filters\SelectFilter::make('created_by')
                    ->relationship('creator', 'name')
                    ->label('Created By')
                    ->preload(),

                // Filter for Many-to-Many relationship (Assigned Users)
                Tables\Filters\SelectFilter::make('assignees')
                    ->relationship('assignees', 'name')
                    ->multiple() // Allow filtering by multiple assignees
                    ->label('Assigned Users')
                    ->preload(),
                // ------------------------------------

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }



public static function getKanbanStatuses(): array
{
    return [
        'open' => [
            'label' => 'Open',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-circle',
        ],
        'in_progress' => [
            'label' => 'In Progress',
            'color' => 'warning',
            'icon' => 'heroicon-o-arrow-path',
        ],
        'done' => [
            'label' => 'Done',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
        ],
    ];
}



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
