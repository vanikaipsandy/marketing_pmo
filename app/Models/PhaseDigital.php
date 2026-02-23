<?php

namespace App\Models;

use App\Models\StatusDigital;
use Illuminate\Database\Eloquent\Model;

class PhaseDigital extends Model
{
    protected $table = 'mst_phase_digital';

    protected $fillable = [
        'name',
    ];

    public function statuses()
    {
        return $this->hasMany(StatusDigital::class, 'phase_id');
    }
}

