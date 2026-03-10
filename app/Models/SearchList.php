<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'criteria_json',
        'visible_columns',
        'total_records',
    ];

    protected $casts = [
        'criteria_json' => 'array',
        'visible_columns' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function records()
    {
        return $this->belongsToMany(Record::class, 'search_list_record')
            ->withTimestamps();
    }
}