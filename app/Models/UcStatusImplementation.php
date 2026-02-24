<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcStatusImplementation extends Model
{
    protected $table = 'trs_uc_status_implementation';
    protected $guarded = ['id'];

    public function digitalInitiative()
    {
        return $this->belongsTo(DigitalInitiative::class, 'digital_initiative_id');
    }
}
