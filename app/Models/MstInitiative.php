<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstInitiative extends Model
{
    protected $table = 'mst_initiative';

    protected $fillable = [
        'coe_id',
        'tipe_initiative',
        'business_unit',
        'code',
        'name',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'coe_id' => 'integer',
            'tipe_initiative' => 'integer',
            'business_unit' => 'integer',
            'code' => 'integer',
        ];
    }
}
