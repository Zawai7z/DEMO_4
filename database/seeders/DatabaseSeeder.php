<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed data awal User dan katalog Media FlipPlay beserta watchlist contoh.
     */
    public function run(): void
    {
        $fajar = User::factory()->create([
            'name' => 'Fajar Pratama',
            'email' => 'fajar@student.umm.ac.id',
        ]);

        User::factory()->create([
            'name' => 'Zaidan Diandra',
            'email' => 'zaidan@student.umm.ac.id',
        ]);

        $this->call([
            MediaSeeder::class,
        ]);

        $starterMediaIds = Media::whereIn('title', [
            "Frieren: Beyond Journey's End",
            'Kimi no Na wa (Your Name)',
        ])->pluck('id');

        $fajar->media()->attach($starterMediaIds);
    }
}
