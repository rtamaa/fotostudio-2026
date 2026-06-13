<div wire:poll.5s="refreshSlots">
    <div class="max-w-6xl mx-auto px-4 py-8">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-6">
                <h1 class="text-2xl font-bold text-white">📸 Pilih Jadwal Foto</h1>
            </div>

            <!-- MONTH + YEAR -->
            <div class="p-6 flex gap-4 items-center border-b">

                <select wire:model="currentMonth" class="border rounded px-3 py-2">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>

                <select wire:model="currentYear"
                    class="border rounded px-4 py-2"
                    style="width:120px">
                @for($y = date('Y'); $y <= date('Y') + 10; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            </div>

            <!-- CALENDAR HEADER -->
            <div class="p-6 border-b">

                <div class="grid grid-cols-7 text-center font-semibold mb-2">
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                    <div>Min</div>
                </div>

                <!-- CALENDAR GRID -->
                <div class="grid grid-cols-7 gap-2">

                    @foreach($calendarDays as $day)

                        @php
                            $isSelected = $selectedDate == $day['date'];
                            $isOutsideMonth = !$day['isCurrentMonth'];
                        @endphp

                        <button
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="p-3 rounded-lg text-sm transition

                            {{ $isSelected ? 'bg-pink-600 text-white' : '' }}

                            {{ $isOutsideMonth ? 'text-gray-300 bg-gray-50' : 'bg-white hover:bg-gray-100' }}"
                        >
                            {{ $day['day'] }}
                        </button>

                    @endforeach

                </div>
            </div>

            <!-- PACKAGE -->
            <div class="p-6 border-b">
                <h2 class="font-semibold mb-4">Pilih Paket:</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    @forelse($packages as $package)
                        <div wire:click="$set('selectedPackage', {{ $package->id }})"
                             class="border rounded-lg p-4 cursor-pointer transition
                             {{ $selectedPackage == $package->id ? 'bg-pink-50 border-pink-500' : 'hover:border-pink-300' }}">

                            <h3 class="font-bold">{{ $package->name }}</h3>
                            <p class="text-pink-600 font-bold">{{ $package->formatted_price }}</p>
                            <p class="text-gray-500 text-sm">⏱️ {{ $package->duration }} menit</p>

                        </div>
                    @empty
                        <p class="text-gray-500 col-span-3 text-center">Belum ada paket tersedia</p>
                    @endforelse

                </div>
            </div>

            <!-- SLOT (STATUS DI SINI - BUKAN CALENDAR) -->
            @if($selectedPackage)
                <div class="p-6">

                    <h3 class="font-semibold mb-4">
                        Slot {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                    </h3>

                    @if(count($availableSlots) > 0)

                        <div class="grid grid-cols-3 md:grid-cols-5 gap-3">

                            @foreach($availableSlots as $slot)

                                @php
                                    $status = $slot['status'] ?? 'available';
                                @endphp

                                <button
                                    wire:click="selectSlot('{{ $slot['start'] }}')"
                                    class="px-4 py-3 rounded-lg text-center text-sm transition

                                    @if($status == 'available')
                                        bg-green-50 border border-green-300 hover:bg-green-100
                                    @elseif($status == 'pending')
                                        bg-yellow-100 border border-yellow-400
                                    @elseif($status == 'confirmed')
                                        bg-blue-100 border border-blue-400
                                    @elseif($status == 'blocked')
                                        bg-gray-200 text-gray-500
                                    @endif

                                    {{ $selectedSlot == $slot['start'] ? 'ring-2 ring-pink-500' : '' }}"
                                >
                                    {{ $slot['display'] }}

                                    <div class="text-xs mt-1">
                                        {{ ucfirst($status) }}
                                    </div>

                                </button>

                            @endforeach

                        </div>

                    @else
                        <p class="text-gray-500">Tidak ada slot tersedia</p>
                    @endif

                </div>
            @endif

            <!-- FORM -->
            @if($showBookingForm && $selectedPackage && $selectedSlot)
                <div class="border-t p-6 bg-gray-50">

                    @livewire('booking.booking-form', [
                        'packageId' => $selectedPackage,
                        'bookingDate' => $selectedDate,
                        'startTime' => $selectedSlot,
                    ])

                </div>
            @endif

        </div>
    </div>
</div>