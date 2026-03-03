<?php

namespace App\Http\Controllers\ProgramPlanning\ProgramDefinition\DigitalInitiatives;

use App\Http\Controllers\Controller;
use App\Models\DigitalInitiative;
use App\Models\InitiativeStatus;
use App\Models\MstInitiative;
use App\Models\StatusMstInitiative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(): Response|RedirectResponse
    {
        if (request()->user()?->isAdminUser()) {
            return redirect()->route('admin.dashboard');
        }

        $statusOptions = InitiativeStatus::ordered()
            ->map(fn (InitiativeStatus $status) => [
                'id' => (int) $status->id,
                'name' => $status->name,
                'label' => ucfirst($status->name),
            ])
            ->values();

        // Count latest status per mst_initiative from trs_status_mstinitiative
        $latestIds = StatusMstInitiative::query()
            ->select(DB::raw('MAX(id) as id'))
            ->whereHas('initiative', fn ($q) => $q->where('tipe_initiative', 1))
            ->groupBy('initiative_id');

        $statusCounts = StatusMstInitiative::query()
            ->joinSub($latestIds, 'latest', fn ($join) => $join->on('trs_status_mstinitiative.id', '=', 'latest.id'))
            ->selectRaw('LOWER(status) as status_key, COUNT(*) as total')
            ->groupBy('status_key')
            ->pluck('total', 'status_key');

        // For initiatives whose latest status is "postpone", find the status
        // just before postpone so the frontend knows which main node to branch from.
        // Result: { "drafting": 1, "propose": 2, ... }
        $postponeFromCounts = [];
        $postponedInitiativeIds = StatusMstInitiative::query()
            ->joinSub($latestIds, 'latest', fn ($join) => $join->on('trs_status_mstinitiative.id', '=', 'latest.id'))
            ->whereRaw('LOWER(status) = ?', ['postpone'])
            ->pluck('initiative_id');

        if ($postponedInitiativeIds->isNotEmpty()) {
            // For each postponed initiative, get the second-to-last status entry
            $prevStatuses = StatusMstInitiative::query()
                ->whereIn('initiative_id', $postponedInitiativeIds)
                ->whereRaw('LOWER(status) != ?', ['postpone'])
                ->select('initiative_id', DB::raw('MAX(id) as prev_id'))
                ->groupBy('initiative_id');

            $postponeFromCounts = StatusMstInitiative::query()
                ->joinSub($prevStatuses, 'prev', fn ($join) => $join->on('trs_status_mstinitiative.id', '=', 'prev.prev_id'))
                ->selectRaw('LOWER(trs_status_mstinitiative.status) as from_key, COUNT(*) as total')
                ->groupBy('from_key')
                ->pluck('total', 'from_key');
        }

        $masterSelectColumns = [
            'id',
            'coe_id',
            'tipe_initiative',
            'business_unit',
            'code',
            'name',
            'description',
            'status',
        ];
        if (Schema::hasColumn('mst_initiative', 'project_id')) {
            $masterSelectColumns[] = 'project_id';
        }

        $masterDigitalInitiatives = MstInitiative::query()
            ->select($masterSelectColumns)
            ->with([
                'coe:id,name',
                'organization:id,name,groub_id',
                'organization.groub:id,name',
                'latestStatus',
            ])
            ->where('tipe_initiative', 1)
            ->orderBy('code')
            ->get()
            ->values();

        $initiativeItems = DigitalInitiative::query()
            ->select(['id', 'no', 'useCase'])
            ->with(['latestScStatusImplementation'])
            ->get();

        return Inertia::render('ProgramPlanning/ProgramDefinition/DigitalInitiatives/Index', [
            'totalDigitalInitiatives' => $masterDigitalInitiatives->count(),
            'masterDigitalInitiatives' => $masterDigitalInitiatives,
            'initiativeItems' => $initiativeItems,
            'statusOptions' => $statusOptions,
            'statusCounts' => $statusCounts,
            'postponeFromCounts' => $postponeFromCounts,
        ]);
    }
}
