<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Seed katalog awal FlipPlay dari data simulasi Modul 3.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => "Frieren: Beyond Journey's End",
                'type' => 'series',
                'genres' => ['Adventure', 'Fantasy', 'Drama'],
                'score' => 9.38,
                'synopsis' => 'Kisah perjalanan seorang penyihir elf bernama Frieren setelah kelompok pahlawan berhasil mengalahkan Raja Iblis, menyelami arti waktu dan persahabatan manusia.',
                'episodes' => 28,
                'status' => 'Completed',
                'season' => 'Fall 2023',
                'duration_minutes' => null,
            ],
            [
                'title' => 'Solo Leveling: Arise',
                'type' => 'series',
                'genres' => ['Action', 'Fantasy', 'Adventure'],
                'score' => 8.72,
                'synopsis' => 'Sung Jin-woo, hunter terlemah di dunia, mendapatkan kemampuan misterius yang memungkinkannya menaikkan level tanpa batas setelah selamat dari double dungeon.',
                'episodes' => 12,
                'status' => 'Completed',
                'season' => 'Winter 2024',
                'duration_minutes' => null,
            ],
            [
                'title' => 'Jujutsu Kaisen Season 2',
                'type' => 'series',
                'genres' => ['Action', 'Supernatural', 'Dark Fantasy'],
                'score' => 8.91,
                'synopsis' => 'Pertempuran dahsyat di distrik Shibuya yang mempertemukan para penyihir jujutsu melawan kutukan tingkat khusus yang dipimpin oleh Suguru Geto.',
                'episodes' => 23,
                'status' => 'Completed',
                'season' => 'Summer 2023',
                'duration_minutes' => null,
            ],
            [
                'title' => 'Kimi no Na wa (Your Name)',
                'type' => 'movie',
                'genres' => ['Romance', 'Drama', 'Supernatural'],
                'score' => 8.85,
                'synopsis' => 'Dua remaja SMA, Mitsuha di pedesaan dan Taki di Tokyo, secara misterius bertukar tubuh dan terhubung melintasi ruang dan waktu.',
                'episodes' => null,
                'status' => null,
                'season' => null,
                'duration_minutes' => 107,
            ],
            [
                'title' => 'Suzume no Tojimari',
                'type' => 'movie',
                'genres' => ['Adventure', 'Fantasy', 'Mystery'],
                'score' => 8.35,
                'synopsis' => 'Petualangan gadis 17 tahun bernama Suzume bersama pemuda pengelana untuk menutup pintu-pintu bencana misterius di seantero Jepang.',
                'episodes' => null,
                'status' => null,
                'season' => null,
                'duration_minutes' => 122,
            ],
        ];

        foreach ($items as $item) {
            Media::create($item);
        }
    }
}

