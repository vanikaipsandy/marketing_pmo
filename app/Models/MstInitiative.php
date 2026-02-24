<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function coe(): BelongsTo
    {
        return $this->belongsTo(TrsCoe::class, 'coe_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(TrsOrganization::class, 'business_unit');
    }
}
