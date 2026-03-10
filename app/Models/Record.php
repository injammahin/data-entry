<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'import_id',
        'row_number',
        'data_json',
    ];

    protected $casts = [
        'data_json' => 'array',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function searchLists()
    {
        return $this->belongsToMany(SearchList::class, 'search_list_record')
            ->withTimestamps();
    }
}