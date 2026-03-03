<?php

namespace App\Http\Controllers\ProgramPlanning;

use App\Http\Controllers\Concerns\ResolvesInitiativeStatus;
use App\Http\Controllers\Controller;
use App\Models\MstInitiative;
use App\Models\StatusMstInitiative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use ResolvesInitiativeStatus;

    public function __invoke(Request $request): Response|RedirectResponse
    {
        if (request()->user()?->isAdminUser()) {
            return redirect()->route('admin.dashboard');
        }

        $filters = [
            'search'          => $request->string('search')->toString(),
            'category_fase'   => $request->input('category_fase'),
            'source_id'       => $request->input('source_id'),
            'groub_id'        => $request->input('groub_id'),
            'phase_id'        => $request->input('phase_id'),
            'organization_id'  => $request->input('organization_id'),
            'coe_id'          => $request->input('coe_id'),
        ];

        $options = $this->dashboardOptions();
        $statusOptions = $this->statusOptions();
        $baselineStatusId = $this->baselineStatusId($statusOptions);

        // Status counts from trs_status_mstinitiative grouped by tipe_initiative
        $digitalStatusCounts = $this->mstStatusCounts(1); // tipe 1 = Digital
        $itStatusCounts      = $this->mstStatusCounts(2); // tipe 2 = IT

        $totalDigital = MstInitiative::where('tipe_initiative', 1)->count();
        $totalIt      = MstInitiative::where('tipe_initiative', 2)->count();

        // All mst_initiative with relationships for the unified table
        $mstInitiatives = MstInitiative::with(['coe', 'organization.groub', 'latestStatus'])
            ->orderBy('tipe_initiative')
            ->orderBy('id')
            ->get();

        return Inertia::render('ProgramPlanning/Dashboard', [
            'summary' => [
                'total_it_initiatives'       => $totalIt,
                'total_digital_initiatives'  => $totalDigital,
                'total_all_initiatives'      => $totalDigital + $totalIt,
                'status_options'             => $statusOptions,
                'it_status_counts'           => $itStatusCounts,
                'digital_status_counts'      => $digitalStatusCounts,
            ],
            'mstInitiatives'         => $mstInitiatives,
            'completedStatusId'      => $baselineStatusId,
            'filters'               => $filters,
            'options'               => $options,
            'categoryOptions'       => $this->categoryOptions(),
        ]);
    }

    // ── Private helpers ──────────────────────────────────────────────────

    private function dashboardOptions(): array
    {
        // Dashboard Program Planning sekarang memakai data master `mst_digitalInitiative`
        // (model `DigitalInitiative`), jadi opsi filter berbasis tabel transaksi tidak dipakai.
        return [
            'sources'        => collect(),
            'groubs'         => collect(),
            'phases'         => collect(),
            'organizations'  => collect(),
            'coes'           => collect(),
            'rjpps'          => collect(),
        ];
    }

    private function mstStatusCounts(int $tipeInitiative): array
    {
        // Get latest status per initiative from trs_status_mstinitiative
        $latestIds = StatusMstInitiative::query()
            ->select(DB::raw('MAX(id) as id'))
            ->whereHas('initiative', fn ($q) => $q->where('tipe_initiative', $tipeInitiative))
            ->groupBy('initiative_id');

        $rawCounts = StatusMstInitiative::query()
            ->joinSub($latestIds, 'latest', fn ($join) => $join->on('trs_status_mstinitiative.id', '=', 'latest.id'))
            ->selectRaw('LOWER(status) as status_key, COUNT(*) as total')
            ->groupBy('status_key')
            ->pluck('total', 'status_key');

        // Normalize aliases (approve → approved, draft → drafting, etc.)
        $aliasMap = [
            'draft'    => 'drafting',
            'approve'  => 'approved',
            'aproved'  => 'approved',
        ];

        $normalized = [];
        foreach ($rawCounts as $key => $total) {
            $canonical = $aliasMap[$key] ?? $key;
            $normalized[$canonical] = ($normalized[$canonical] ?? 0) + $total;
        }

        return $normalized;
    }

    private function categoryOptions(): array
    {
        return [
            ['id' => 1, 'label' => 'Planning'],
            ['id' => 2, 'label' => 'Implementation'],
        ];
    }
}