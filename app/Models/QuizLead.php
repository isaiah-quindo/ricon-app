<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class QuizLead extends Model
{
    use HasUuids;

    public const SOURCE_21K_QUIZ = '21k_quiz';

    protected $fillable = [
        'first_name',
        'email',
        'source',
        'score',
        'result',
    ];

    protected $casts = [
        'score'               => 'integer',
        'mailchimp_synced_at' => 'datetime',
    ];

    public function getResultLabelAttribute(): string
    {
        return match ($this->result) {
            'a' => 'Ready for 21K',
            'b' => 'Almost there',
            'c' => 'Go 10K first',
            default => $this->result,
        };
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('email', 'like', "%{$term}%")
              ->orWhere('first_name', 'like', "%{$term}%");
        });
    }
}
