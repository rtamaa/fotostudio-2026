<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingStep;

class BookingStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================
        // DEFAULT BOOKING STEPS
        // =========================

        BookingStep::create([
            'step_number' => 1,
            'title' => 'Pilih Paket',
            'description' => 'Pilih paket foto favoritmu',
            'icon' => '📦',
        ]);

        BookingStep::create([
            'step_number' => 2,
            'title' => 'Pilih Jadwal',
            'description' => 'Pilih tanggal & jam yang tersedia',
            'icon' => '📅',
        ]);

        BookingStep::create([
            'step_number' => 3,
            'title' => 'Transfer & Upload',
            'description' => 'Transfer pembayaran dan upload bukti',
            'icon' => '💳',
        ]);

        BookingStep::create([
            'step_number' => 4,
            'title' => 'Konfirmasi Admin',
            'description' => 'Admin akan konfirmasi booking kamu',
            'icon' => '✅',
        ]);
    }
}