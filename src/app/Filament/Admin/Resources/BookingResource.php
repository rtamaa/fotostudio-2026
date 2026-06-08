<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Services\BookingService;
use App\Services\SlotService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    public static function form(Form $form): Form
    {
        $slotService = app(SlotService::class);

        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),

            Forms\Components\Select::make('package_id')
                ->relationship('package', 'name')
                ->required(),

            Forms\Components\DatePicker::make('booking_date')
                ->required()
                ->minDate(now()->addDay()),

            Forms\Components\Select::make('start_time')
                ->options(function (callable $get) use ($slotService) {
                    $date = $get('booking_date');
                    if (!$date) return [];

                    return collect($slotService->getAvailableSlots($date))
                        ->pluck('display', 'start');
                })
                ->required(),

            Forms\Components\Textarea::make('special_request'),

            Forms\Components\Select::make('booking_status')
                ->options(collect(BookingStatus::cases())
                    ->mapWithKeys(fn ($status) => [
                        $status->value => $status->label()
                    ])
                )
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),

                Tables\Columns\TextColumn::make('user.name'),

                Tables\Columns\TextColumn::make('package.name'),

                Tables\Columns\TextColumn::make('booking_date')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i'),

                Tables\Columns\BadgeColumn::make('booking_status')
                    ->colors([
                        'success' => 'confirmed',
                        'warning' => 'pending',
                        'danger'  => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => $state?->label()),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm_payment')
                    ->action(function (Booking $record, BookingService $service) {
                        $result = $service->confirmBooking($record);

                        Notification::make()
                            ->title($result['success'] ? 'Berhasil' : 'Gagal')
                            ->body($result['message'] ?? '')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->isPending()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}