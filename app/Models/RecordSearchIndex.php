<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordSearchIndex extends Model
{
    protected $table = 'record_search_indexes';

    protected $primaryKey = 'record_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'record_id',
        'state_id',
        'business_name_norm',
        'executive_first_name_norm',
        'executive_last_name_norm',
        'executive_title_norm',
        'city_norm',
        'address_norm',
        'zip_code_norm',
        'phone_norm',
        'sic_description_norm',
        'has_email',
        'has_real_email',
        'has_hashed_email',
        'has_direct_mail',
    ];

    protected $casts = [
        'has_email' => 'boolean',
        'has_real_email' => 'boolean',
        'has_hashed_email' => 'boolean',
        'has_direct_mail' => 'boolean',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class, 'record_id');
    }
}