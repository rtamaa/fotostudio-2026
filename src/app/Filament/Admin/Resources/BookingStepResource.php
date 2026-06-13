<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookingStepResource\Pages;
use App\Models\BookingStep;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingStepResource extends Resource
{
    protected static ?string $model = BookingStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Cara Booking';

    protected static ?string $modelLabel = 'Step Booking';

    protected static ?string $pluralModelLabel = 'Step Booking';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('step_number')
                    ->label('Nomor Langkah')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('icon')
                    ->label('Icon Emoji')
                    ->placeholder('📦')
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('step_number')
            ->columns([
                Tables\Columns\TextColumn::make('step_number')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingSteps::route('/'),
            'create' => Pages\CreateBookingStep::route('/create'),
            'edit' => Pages\EditBookingStep::route('/{record}/edit'),
        ];
    }
}