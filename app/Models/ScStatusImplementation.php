<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScStatusImplementation extends Model
{
    protected $table = 'trs_sc_status_implementation';
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function digitalInitiative()
    {
        return $this->belongsTo(DigitalInitiative::class, 'digital_initiative_id');
    }
}
