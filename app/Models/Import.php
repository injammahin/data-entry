<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'file_name',
        'original_name',
        'file_hash',
        'total_rows',
        'processed_rows',
        'successful_rows',
        'skipped_rows',
        'status',
        'headers',
        'error_message',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'headers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}