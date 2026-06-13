<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $feedbacks = [
            [
                'user_id' => 2,
                'name' => 'Rina',
                'message' => 'Tolong tambah pilihan background lebih banyak ya kak.',
            ],
            [
                'user_id' => 3,
                'name' => 'Budi',
                'message' => 'Sistem booking sudah bagus, tapi kalau bisa ada reminder WA.',
            ],
            [
                'user_id' => 4,
                'name' => 'Dinda',
                'message' => 'Tempatnya sudah nyaman, semoga ada promo bundle keluarga.',
            ],

            // USER "SEPTI"
            [
                'user_id' => 6,
                'name' => 'Septi',
                'message' => 'Kak, bisa gak nanti ada paket couple lebih murah? 😄',
            ],
        ];

        foreach ($feedbacks as $feedback) {
            Feedback::create($feedback);
        }
    }
}