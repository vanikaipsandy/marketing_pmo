<?php

namespace App\Models;

use App\Models\MstCoe;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MstInitiative extends Model
{
    protected $table = 'mst_initiative';

    protected $fillable = [
        'coe_id',
        'tipe_initiative',
        'business_unit',
        'project_id',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
