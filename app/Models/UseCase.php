<?php

namespace App\Models;

use App\Models\Coe;
use App\Models\TrsDigitalInitiative;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UseCase extends Model
{
    protected $table = 'trs_use_case';

    protected $fillable = [
        'coe_id',
        'name',
    ];

    public function coe(): BelongsTo
    {
        return $this->belongsTo(Coe::class, 'coe_id');
    }

    public function digitalInitiatives()
    {
        return $this->belongsToMany(TrsDigitalInitiative::class, 'trs_digital_initiative', 'useCase_id', 'id');
    }
}
