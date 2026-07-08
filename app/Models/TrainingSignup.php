<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class TrainingSignup extends Model
{
    use HasUuids;

    public const TOTAL_WEEKS = 24;

    // One shared calendar for everyone (matches program_start in resources/data/training_program.json)
    public const PROGRAM_START = '2026-06-01';

    protected $fillable = [
        'first_name',
        'email',
        'plan',
        'registered_tgc',
        'started_on',
    ];

    protected $casts = [
        'registered_tgc'      => 'boolean',
        'started_on'          => 'date',
        'link_last_sent_at'   => 'datetime',
        'mailchimp_synced_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $signup) {
            $signup->token ??= Str::random(64);
            $signup->started_on ??= now()->toDateString();
        });
    }

    // Magic-link URL — the token is the bearer credential, never expose the PK publicly
    public function getProgramUrlAttribute(): string
    {
        return route('training.program', $this->token);
    }

    // The week the whole program is on right now, clamped to 1..24
    public static function currentProgramWeek(): int
    {
        $days = (int) \Illuminate\Support\Carbon::parse(self::PROGRAM_START)->diffInDays(today());

        return min(self::TOTAL_WEEKS, max(1, intdiv($days, 7) + 1));
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('email', 'like', "%{$term}%")
              ->orWhere('first_name', 'like', "%{$term}%");
        });
    }
}
