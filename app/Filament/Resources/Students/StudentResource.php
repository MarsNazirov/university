<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Student';

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->label('ФИО'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('group')->label('Группа'),
                TextColumn::make('course')->label('Курс'),
                TextColumn::make('status')
                ->label('Статус')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'active'          => 'Активный',
                    'expelled'        => 'Отчислен',
                    'academic_leave'  => 'Академический отпуск',
                    default           => $state,
                })
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'active'          => 'success',
                    'expelled'        => 'danger',
                    'academic_leave'  => 'warning',
                    default           => 'gray',
                }),
                TextColumn::make('room.number')->label('Комната'),
            ])
            ->filters([
                // можно добавить фильтры по статусу, группе и т.д.
            ])
            ->actions([
                // Действие "Выселить"
                Action::make('evict')
                    ->label('Выселить')
                    ->icon('heroicon-o-user-minus')
                    ->visible(fn (Student $record): bool => !is_null($record->room_id))
                    ->requiresConfirmation()
                    ->action(function (Student $record) {
                        $room = $record->room;
                        $record->room_id = null;
                        $record->save();

                        if ($room) {
                            $room->load('students');
                            // Если комната была занята и освободилось место
                            if ($room->students()->count() < $room->beds_count && $room->status === 'occupied') {
                                $room->status = 'available';
                                $room->save();
                            }
                        }

                        Notification::make()
                            ->title('Студент выселен')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // можно добавить массовые действия
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('room');
    }
}
