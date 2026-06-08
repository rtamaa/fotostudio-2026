<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudioBlockResource\Pages;
use App\Models\StudioBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StudioBlockResource extends Resource
{
    protected static ?string $model = StudioBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';
    protected static ?string $navigationGroup = 'Pengaturan';

    // =========================
    // FORM
    // =========================

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->minDate(now()),

                Forms\Components\TimePicker::make('start_time')
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->required(),

                Forms\Components\TextInput::make('reason')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => Auth::id()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('end_time')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('reason'),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh'),
            ])
            ->defaultSort('date', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudioBlocks::route('/'),
            'create' => Pages\CreateStudioBlock::route('/create'),
            'edit' => Pages\EditStudioBlock::route('/{record}/edit'),
        ];
    }
}