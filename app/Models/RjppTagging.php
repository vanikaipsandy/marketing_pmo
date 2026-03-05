<?php

namespace App\Models;

use App\Models\Theme;
use App\Models\TrsDigitalInitiative;
use Illuminate\Database\Eloquent\Model;

class RjppTagging extends Model
{
    protected $table = 'trs_rjpp';

    public $incrementing = false;

    protected $fillable = [
        'digital_id',
        'theme_id',
    ];

    public $timestamps = true;

    public function digitalInitiative()
    {
        return $this->belongsTo(TrsDigitalInitiative::class, 'digital_id');
    }

    public function rjpp()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
