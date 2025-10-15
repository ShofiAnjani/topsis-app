<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Contoh user bawaan
        User::factory()->create([
            'name' => 'shofi',
            'email' => 'shofie@spk.app',
        ]);

        // ============================
        // Data Alternatif
        // ============================
        $alternatives = [
            ['name' => 'Bud', 'address' => 'Jl. Merdeka 1'],
            ['name' => 'Ani', 'address' => 'Jl. Sudirman 2'],
            ['name' => 'Citra', 'address' => 'Jl. Diponegoro 3'],
        ];

        foreach ($alternatives as $alt) {
            Alternative::create($alt);
        }

        // ============================
        // Data Kriteria
        // ============================
        $criteria = [
            ['name' => 'Penghasilan', 'type' => 'cost', 'weight' => 0.4],
            ['name' => 'Jumlah Tanggungan', 'type' => 'benefit', 'weight' => 0.3],
            ['name' => 'Kondisi Rumah', 'type' => 'cost', 'weight' => 0.2],
        ];

        foreach ($criteria as $crit) {
            Criterion::create($crit);
        }

        // ============================
        // Data Skor
        // ============================
        $scores = [
            // Bud
            ['alternative_id' => 1, 'criterion_id' => 1, 'value' => 2000000],
            ['alternative_id' => 1, 'criterion_id' => 2, 'value' => 3],
            ['alternative_id' => 1, 'criterion_id' => 3, 'value' => 1],

            // Ani
            ['alternative_id' => 2, 'criterion_id' => 1, 'value' => 3000000],
            ['alternative_id' => 2, 'criterion_id' => 2, 'value' => 4],
            ['alternative_id' => 2, 'criterion_id' => 3, 'value' => 3],

            // Citra
            ['alternative_id' => 3, 'criterion_id' => 1, 'value' => 2500000],
            ['alternative_id' => 3, 'criterion_id' => 2, 'value' => 2],
            ['alternative_id' => 3, 'criterion_id' => 3, 'value' => 4],
        ];

        foreach ($scores as $score) {
            Score::create($score);
        }
    }
}