<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProjectCharter;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'trs_projects';

    protected $fillable = ['code', 'name', 'owner_id', 'status', 'metadata'];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'status' => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────── */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function statusRef(): BelongsTo
    {
        return $this->belongsTo(InitiativeStatus::class, 'status');
    }

    public function charter(): HasOne
    {
        return $this->hasOne(ProjectCharter::class)->latestOfMany();
    }

    public function charters(): HasMany
    {
        return $this->hasMany(ProjectCharter::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('order');
    }

    public function pcStatusImplementations(): HasMany
    {
        return $this->hasMany(PcStatusImplementation::class, 'project_id')->orderBy('date', 'desc')->orderBy('time_start', 'desc');
    }

    public function latestPcStatusImplementation(): HasOne
    {
        return $this->hasOne(PcStatusImplementation::class, 'project_id')->latestOfMany('id');
    }

    public function goals(): BelongsToMany
    {
        return $this->belongsToMany(Goal::class, 'trs_goal_project');
    }

    public function masterInitiatives(): HasMany
    {
        return $this->hasMany(MstInitiative::class, 'project_id');
    }

    public function pcInitiatives(): HasMany
    {
        return $this->hasMany(PcInitiative::class, 'pc_id');
    }

    public function mappedInitiatives(): BelongsToMany
    {
        $tableColumns = \Illuminate\Support\Facades\Schema::getColumnListing('trs_pc_initiative');

        $projectColumn = collect(['project_id', 'trs_project_id', 'pc_id'])->first(
            static fn ($col) => in_array($col, $tableColumns, true)
        ) ?? 'project_id';

        $initiativeColumn = collect(['initiative_id', 'mst_initiative_id', 'useCase_id', 'use_case_id'])->first(
            static fn ($col) => in_array($col, $tableColumns, true)
        ) ?? 'initiative_id';

        return $this->belongsToMany(MstInitiative::class, 'trs_pc_initiative', $projectColumn, $initiativeColumn);
    }

    /* ── Scopes ────────────────────────────────────── */

    public function scopeStatus($query, int $status)
    {
        return $query->where('status', $status);
    }
}
