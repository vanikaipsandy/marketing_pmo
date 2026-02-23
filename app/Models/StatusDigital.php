<?php

namespace App\Models;

use App\Models\PhaseDigital;
use Illuminate\Database\Eloquent\Model;

class StatusDigital extends Model
{
    protected $table = 'trs_status_digital';

    protected $fillable = [
        'phase_id',
        'name',
    ];

    public function phase()
    {
        return $this->belongsTo(PhaseDigital::class, 'phase_id');
    }

    public function histories()
    {
        return $this->hasMany(DigitalHistory::class, 'status_id');
    }

    public function digitalInitiatives()
    {
        return $this->belongsToMany(TrsDigitalInitiative::class, 'trs_digital_history', 'status_id', 'digital_id')->withTimestamps();
    }
}
