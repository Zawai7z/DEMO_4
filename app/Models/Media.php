<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'title',
        'type',
        'genres',
        'score',
        'synopsis',
        'episodes',
        'status',
        'season',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'genres' => 'array',
            'score' => 'float',
            'episodes' => 'integer',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Relasi many-to-many: satu Media bisa dikoleksi banyak User.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Query scope untuk mencari media berdasarkan judul.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where('title', 'like', '%' . $keyword . '%');
    }
}

