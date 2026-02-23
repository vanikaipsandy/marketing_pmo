<?php

namespace App\Models;

use App\Models\TrsDigitalInitiative;
use Illuminate\Database\Eloquent\Model;

class DigitalDetail extends Model
{
    protected $table = 'trs_digital_details';

    protected $fillable = [
        'digital_id',
        'useCase_id',
        'useCase_description',
        'current_situation',
        'key_functionalities',
        'value_detail',
        'urgency_detail',
        'ease_implementation',
        'ease_detail',
        'resource_requirement',
        'resource_detail',
        'interdependencies',
        'sign_by'
    ];

    public function digitalInitiative()
    {
        return $this->belongsTo(TrsDigitalInitiative::class, 'digital_id');
    }
}
