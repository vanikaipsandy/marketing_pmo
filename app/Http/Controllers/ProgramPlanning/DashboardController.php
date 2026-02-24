<?php

namespace App\Http\Controllers\ProgramPlanning;

use App\Http\Controllers\Concerns\ResolvesInitiativeStatus;
use App\Http\Controllers\Controller;
use App\Models\DigitalInitiative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $emptyItStatusCounts = $this->mapCountsByStatus($statusOptions, collect());
        $digitalStatusCounts = $this->digitalStatusCountsByStatus();

        $openDigitalInitiatives = $this->openDigitalInitiatives();
        $openItInitiatives = collect();

        return Inertia::render('ProgramPlanning/Dashboard', [
            'summary' => [
                'total_it_initiatives'       => 0,
                'total_digital_initiatives'  => DigitalInitiative::query()->count(),
                'total_all_initiatives'      => DigitalInitiative::query()->count(),
                'status_options'             => $statusOptions,
                'it_status_counts'           => $emptyItStatusCounts,
                'digital_status_counts'      => $digitalStatusCounts,
                'combined_status_counts'     => $digitalStatusCounts,
                'status_rows'                => $this->statusRows($statusOptions, $emptyItStatusCounts, $digitalStatusCounts),
            ],
            'completedStatusId'      => $baselineStatusId,
            'openDigitalInitiatives' => $openDigitalInitiatives,
            'openItInitiatives'     => $openItInitiatives,
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

    private function digitalStatusCountsByStatus(): array
    {
        $statusOptions = $this->statusOptions();
        $rawCounts = DigitalInitiative::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return $this->mapCountsByStatus($statusOptions, $rawCounts);
    }

    private function openDigitalInitiatives(): Collection
    {
        return DigitalInitiative::query()
            ->latest()
            ->get();
    }

    private function categoryOptions(): array
    {
        return [
            ['id' => 1, 'label' => 'Planning'],
            ['id' => 2, 'label' => 'Implementation'],
        ];
    }

    private function statusRows(array $statusOptions, array $itCounts, array $digitalCounts): array
    {
        return collect($statusOptions)
            ->map(function (array $status) use ($itCounts, $digitalCounts): array {
                $statusId     = (string) $status['id'];
                $itCount      = (int) ($itCounts[$statusId] ?? 0);
                $digitalCount = (int) ($digitalCounts[$statusId] ?? 0);

                return [
                    'id'      => (int) $status['id'],
                    'name'    => $status['name'],
                    'label'   => $status['label'],
                    'it'      => $itCount,
                    'digital' => $digitalCount,
                    'total'   => $itCount + $digitalCount,
                ];
            })
            ->values()
            ->all();
    }
}