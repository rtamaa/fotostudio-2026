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
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        $slotService = app(SlotService::class);
        
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->required(),
                                
                                Forms\Components\Select::make('package_id')
                                    ->relationship('package', 'name')
                                    ->searchable()
                                    ->required(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('booking_date')
                                    ->required()
                                    ->minDate(now()->addDay()),
                                
                                Forms\Components\Select::make('start_time')
                                    ->options(function (callable $get) use ($slotService) {
                                        $date = $get('booking_date');
                                        if (!$date) return [];
                                        $slots = $slotService->getAvailableSlots($date);
                                        return collect($slots)->pluck('display', 'start');
                                    })
                                    ->required(),
                            ]),
                        
                        Forms\Components\Textarea::make('special_request')
                            ->rows(3),
                        
                        Forms\Components\Select::make('booking_status')
                            ->options(collect(BookingStatus::cases())->map(fn($status) => [
                                $status->value => $status->label(),
                            ])->toArray())
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking ID')
                    ->limit(8)
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Paket'),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i'),
                Tables\Columns\BadgeColumn::make('booking_status')
                    ->colors([
                        'success' => 'confirmed',
                        'warning' => 'pending',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => $state instanceof BookingStatus ? $state->label() : BookingStatus::from($state)->label())
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('booking_status')
                    ->options(collect(BookingStatus::cases())->map(fn($status) => [
                        $status->value => $status->label(),
                    ])->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Reschedule')
                    ->visible(fn (Booking $record) => $record->canBeRescheduledBy(auth()->user())),
                
                Tables\Actions\Action::make('confirm_payment')
                    ->label('Confirm Payment')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Booking $record, BookingService $bookingService) {
                        $result = $bookingService->confirmBooking($record);
                        Notification::make()
                            ->title($result['success'] ? 'Berhasil' : 'Gagal')
                            ->body($result['message'])
                            ->{$result['success'] ? 'success' : 'danger'}()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->isPending()),
                
                Tables\Actions\Action::make('cancel_booking')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->label('Alasan'),
                    ])
                    ->action(function (Booking $record, array $data, BookingService $bookingService) {
                        $result = $bookingService->cancelBooking($record, $data['reason']);
                        Notification::make()
                            ->title($result['success'] ? 'Berhasil' : 'Gagal')
                            ->body($result['message'])
                            ->{$result['success'] ? 'warning' : 'danger'}()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->canBeCancelledBy(auth()->user())),
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