<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Services\SlotService;
use App\Models\Package;
use Carbon\Carbon;

class Calendar extends Component
{
    public $selectedDate;
    public $availableSlots = [];
    public $packages;
    public $selectedPackage = null;
    public $selectedSlot = null;
    public $showBookingForm = false;

    public function mount()
    {
        $this->selectedDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->packages = Package::where('is_active', true)->get();
        $this->loadData();
    }

    public function loadData()
    {
        $slotService = app(SlotService::class);
        $this->availableSlots = $slotService->getAvailableSlots($this->selectedDate);
    }

    // =========================
    // AUTO REFRESH SLOT
    // =========================
    public function refreshSlots()
    {
        $this->loadData();
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

    public function nextDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
        $this->showBookingForm = false;
        $this->selectedSlot = null;
        $this->loadData();
    }

    public function previousDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');

        if (Carbon::parse($this->selectedDate)->isToday()) {
            $this->selectedDate = Carbon::now()->addDay()->format('Y-m-d');
        }

        $this->loadData();
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
        ])->layout('components.layouts.livewire');
    }
}