<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrsReviewPC extends Model
{
    protected $table = 'trs_review_pc';

    protected $fillable = [
        'initiative_id',
        'kesimpulan',
        'detail_penjelasan',
        'penjelasan',
        'why',
        'what',
        'how',
        'project_profile',
        'key_milestone',
        'risk_impact'
    ];

    public function initiative(): BelongsTo
    {
        return $this->belongsTo(MstInitiative::class, 'initiative_id');
    }
}
