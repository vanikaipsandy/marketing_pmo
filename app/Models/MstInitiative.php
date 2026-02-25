<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function coe(): BelongsTo
    {
        return $this->belongsTo(MstCoe::class, 'coe_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(TrsOrganization::class, 'business_unit');
    }

    public function initiativeRelationsRow(): HasMany
    {
        return $this->hasMany(MstInitiativeRelation::class, 'initiative_code_row');
    }

    public function initiativeRelationsColumn(): HasMany
    {
        return $this->hasMany(MstInitiativeRelation::class, 'initiative_code_column');
    }
}
