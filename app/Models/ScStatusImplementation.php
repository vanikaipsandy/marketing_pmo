<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScStatusImplementation extends Model
{
    protected $table = 'trs_sc_status_implementation';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /* ── Relationships ─────────────────────────────── */

    /**
     * The digital initiative (mst_digitalInitiative) this status belongs to.
     */
    public function digitalInitiative(): BelongsTo
    {
        return $this->belongsTo(DigitalInitiative::class, 'digital_initiative_id');
    }

    /**
     * The scope-charter initiative (trs_sc_initiative) this status belongs to.
     */
    public function scInitiative(): BelongsTo
    {
        return $this->belongsTo(TrsDigitalInitiative::class, 'sc_initiative_id');
    }
}
