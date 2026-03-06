<?php

namespace App\Filament\Resources\Rooms;

use App\Filament\Resources\Rooms\Pages\CreateRoom;
use App\Filament\Resources\Rooms\Pages\EditRoom;
use App\Filament\Resources\Rooms\Pages\ListRooms;
use App\Filament\Resources\Rooms\Schemas\RoomForm;
use App\Filament\Resources\Rooms\Tables\RoomsTable;
use App\Models\Room;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Student;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Room';

    public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            \Filament\Forms\Components\TextInput::make('number')
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Номер комнаты'),
            \Filament\Forms\Components\TextInput::make('floor')
                ->required()
                ->numeric()
                ->label('Этаж'),
            \Filament\Forms\Components\Select::make('type')
                ->required()
                ->options([
                    'male'   => 'Мужская',
                    'female' => 'Женская',
                    'family' => 'Семейная',
                ])
                ->label('Тип'),
            \Filament\Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('₽')
                ->label('Цена'),
            \Filament\Forms\Components\TextInput::make('beds_count')
                ->required()
                ->numeric()
                ->minValue(1)
                ->label('Количество мест'),
            \Filament\Forms\Components\Select::make('status')
                ->required()
                ->options([
                    'available' => 'Свободна',
                    'occupied'  => 'Занята',
                    'repair'    => 'Ремонт',
                ])
                ->default('available')
                ->label('Статус'),
            \Filament\Forms\Components\Textarea::make('description')
                ->nullable()
                ->label('Описание'),
            \Filament\Forms\Components\TextInput::make('photo')
                ->nullable()
                ->label('Фото (ссылка)'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('number')->sortable()->searchable(),
            TextColumn::make('floor')->sortable(),
            TextColumn::make('type'),
            TextColumn::make('price')->money('rub'),
            TextColumn::make('beds_count')->label('Мест'),
            TextColumn::make('status')->badge(),
            // Новая колонка со студентами
            TextColumn::make('students.full_name')
                ->label('Жильцы')
                ->listWithLineBreaks()
                ->limitList(3)
                ->expandableLimitedList(),
        ])
        ->filters([
            // фильтры
        ])
        ->actions([
            Action::make('checkin')
            ->label('Заселить')
            ->icon('heroicon-o-user-plus')
            ->visible(fn (Room $record): bool => $record->status === 'available')
            ->form(function (Room $record) {
                return [
            Select::make('student_id')
                ->label('Студент')
                ->options(
                    Student::whereNull('room_id')
                        ->orWhere('room_id', '!=', $record->id)
                        ->get()
                        ->mapWithKeys(fn ($student) => [$student->id => $student->full_name . ' (' . $student->group . ')'])
                )
                ->searchable()
                ->required(),
        ];
    })
    ->action(function (array $data, Room $record) {
            $student = Student::find($data['student_id']);
            if (!$student) {
                return;
            }

            // Заселяем
            $student->room_id = $record->id;
            $student->save();

            // Обновляем статус комнаты
            $record->load('students');
            if ($record->students()->count() >= $record->beds_count) {
                $record->status = 'occupied';
                $record->save();
            }

            Notification::make()
                ->title('Студент заселен')
                ->success()
                ->send();
        }),
        ])
        ->bulkActions([
            // если нужно
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
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('students');
    }
}