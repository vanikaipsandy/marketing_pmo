<?php

namespace App\Models;

use App\Models\Groub;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'trs_organization';

    protected $fillable = ['groub_id', 'name'];

    public function groub()
    {
        return $this->belongsTo(Groub::class, 'groub_id');
    }

    public function digitalInitiatives()
    {
        return $this->belongsToMany(TrsDigitalInitiative::class, 'trs_digital_groub', 'organization_id', 'digital_id')->withTimestamps();
    }
}
