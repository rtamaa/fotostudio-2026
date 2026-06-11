<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Services\SlotService;
use App\Models\Package;
use App\Models\Booking;
use Carbon\Carbon;

class Calendar extends Component
{
    public $selectedDate;
    public $availableSlots = [];
    public $packages;
    public $selectedPackage = null;
    public $selectedSlot = null;
    public $showBookingForm = false;

    // =========================
    // CALENDAR STATE
    // =========================
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];

    // =========================
    // FUTURE READY: SLOT STATUS CACHE
    // =========================
    public $slotStatusMap = []; // 🔥 nanti dipakai untuk Google Calendar style

    public function mount()
    {
        $this->selectedDate = Carbon::now()->addDay()->format('Y-m-d');

        $this->currentMonth = Carbon::now()->month;
        $this->currentYear  = Carbon::now()->year;

        $this->packages = Package::where('is_active', true)->get();

        $this->generateCalendar();
        $this->loadData();
    }

    public function updatedCurrentMonth()
    {
        $this->generateCalendar();
    }

    public function updatedCurrentYear()
    {
        $this->generateCalendar();
    }

    public function loadData()
    {
        $slotService = app(SlotService::class);

        $slots = $slotService->getAvailableSlots($this->selectedDate);

        // =========================
        // SAFE EXTENSION: SLOT STATUS MAP
        // =========================
        $this->availableSlots = collect($slots)->map(function ($slot) {
            return [
                'start' => $slot['start'],
                'end' => $slot['end'],
                'display' => $slot['display'],

                // default safe status (tidak merusak sistem lama)
                'status' => $slot['status'] ?? 'available',
            ];
        })->toArray();
    }

    // =========================
    // CALENDAR GENERATOR (ADMIN STYLE + GOOGLE READY)
    // =========================
    public function generateCalendar()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);

        // Senin start week (Google Calendar style)
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endDate   = $startOfMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $this->calendarDays = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

            // =========================
            // BASIC CALENDAR DATA
            // =========================
            $this->calendarDays[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'month' => $date->month,
                'isCurrentMonth' => $date->month === $this->currentMonth,

                // =========================
                // FUTURE HOOK (EVENT DENSITY)
                // =========================
                'hasBooking' => Booking::whereDate('booking_date', $date->format('Y-m-d'))
                    ->whereNotIn('booking_status', ['cancelled'])
                    ->exists(),
            ];
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->showBookingForm = false;
        $this->selectedSlot = null;

        $this->loadData();
    }

    public function selectSlot($slot)
    {
        $this->selectedSlot = $slot;
        $this->showBookingForm = true;
    }

    public function refreshSlots()
    {
        $this->loadData();
        $this->generateCalendar();
    }

    // =========================
    // FUTURE READY: STATUS ENGINE
    // =========================
    public function getSlotColor($status)
    {
        return match ($status) {
            'available' => 'bg-green-100',
            'pending' => 'bg-yellow-100',
            'confirmed' => 'bg-blue-100',
            'blocked' => 'bg-gray-300',
            default => 'bg-green-100',
        };
    }

    public function render()
    {
        return view('livewire.booking.calendar', [
            'packages' => $this->packages,
            'selectedDate' => $this->selectedDate,
            'availableSlots' => $this->availableSlots,
            'selectedPackage' => $this->selectedPackage,
            'selectedSlot' => $this->selectedSlot,
            'showBookingForm' => $this->showBookingForm,
            'calendarDays' => $this->calendarDays,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
        ])->layout('components.layouts.livewire');
    }
}