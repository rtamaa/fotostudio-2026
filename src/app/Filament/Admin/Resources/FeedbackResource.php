<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Feedback';

    protected static ?string $modelLabel = 'Feedback';

    protected static ?string $pluralModelLabel = 'Feedback';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengirim')
                    ->maxLength(255),

                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Baru',
                        'read' => 'Sudah Dibaca',
                        'archived' => 'Diarsipkan',
                    ])
                    ->default('new')
                    ->required(),

                Forms\Components\Select::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                    ])
                    ->default('medium')
                    ->required(),

                Forms\Components\Textarea::make('admin_reply')
                    ->label('Balasan Admin')
                    ->rows(4)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Pengirim')
                    ->searchable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')
                    ->limit(60),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'new',
                        'warning' => 'read',
                        'success' => 'archived',
                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),

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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
