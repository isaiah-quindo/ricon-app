<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/** A runner's arrival (in) and departure (out) at one timed checkpoint on race day. */
class CheckpointPass extends Model
{
    use HasUuids;

    protected $fillable = ['registration_id', 'checkpoint', 'in_at', 'out_at', 'recorded_by'];

    protected $casts = [
        'in_at'  => 'immutable_datetime',
        'out_at' => 'immutable_datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
