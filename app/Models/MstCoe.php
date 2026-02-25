<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MstCoe extends Model
{
    protected $table = 'mst_coe';

    protected $fillable = [
        'name',
    ];

    public function initiatives(): HasMany
    {
        return $this->hasMany(MstInitiative::class, 'coe_id');
    }
}
