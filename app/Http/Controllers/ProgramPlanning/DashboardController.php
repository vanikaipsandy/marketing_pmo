<?php

namespace App\Http\Controllers\ProgramPlanning;

use App\Http\Controllers\Concerns\ResolvesInitiativeStatus;
use App\Http\Controllers\Controller;
use App\Models\Coe;
use App\Models\DataSource;
use App\Models\Groub;
use App\Models\Organization;
use App\Models\PhaseDigital;
use App\Models\Theme;
use App\Models\TrsDigitalInitiative;
use App\Models\UseCase;
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
        $statusOptions = $this->statusOptionsFromPhases();
        $baselineStatusId = $this->baselineStatusId($this->statusOptions());

        $emptyItStatusCounts = $this->mapCountsByStatus($statusOptions, collect());
        $digitalStatusCounts = $this->digitalStatusCountsByPhase($filters);

        $openDigitalInitiatives = $this->openDigitalInitiativesFiltered($filters);
        $openItInitiatives = collect();

        return Inertia::render('ProgramPlanning/Dashboard', [
            'summary' => [
                'total_it_initiatives'       => 0,
                'total_digital_initiatives'  => TrsDigitalInitiative::query()->count(),
                'total_all_initiatives'      => TrsDigitalInitiative::query()->count(),
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
        $phaseOrder = ['drafting' => 1, 'review' => 2, 'approve' => 3, 'finish' => 4];
        $phases = PhaseDigital::query()
            ->select(['id', 'name'])
            ->whereIn('name', array_keys($phaseOrder))
            ->get()
            ->sortBy(fn (PhaseDigital $p) => $phaseOrder[strtolower((string) $p->name)] ?? 999)
            ->values();

        $coes = UseCase::query()
            ->with('coe:id,name')
            ->select(['id', 'coe_id'])
            ->whereNotNull('coe_id')
            ->get()
            ->pluck('coe')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn (Coe $c) => ['id' => $c->id, 'name' => $c->name]);

        if ($coes->isEmpty()) {
            $coes = Coe::query()->select(['id', 'name'])->orderBy('name')->get();
        }

        return [
            'sources'        => DataSource::query()->select(['id', 'name', 'month', 'year'])->orderBy('year')->orderBy('month')->get(),
            'groubs'         => Groub::query()->select(['id', 'name'])->orderBy('name')->get(),
            'phases'         => $phases,
            'organizations'  => Organization::query()->select(['id', 'name'])->orderBy('name')->get(),
            'coes'           => $coes,
            'rjpps'          => Theme::query()->select(['id', 'name'])->orderBy('name')->get(),
        ];
    }

    private function statusOptionsFromPhases(): array
    {
        $phaseOrder = ['drafting' => 1, 'review' => 2, 'approve' => 3, 'finish' => 4];
        $phases = PhaseDigital::query()
            ->select(['id', 'name'])
            ->whereIn('name', array_keys($phaseOrder))
            ->get()
            ->sortBy(fn (PhaseDigital $p) => $phaseOrder[strtolower((string) $p->name)] ?? 999)
            ->values();

        return $phases->map(fn (PhaseDigital $p) => [
            'id'    => (int) $p->id,
            'name'  => strtolower((string) $p->name),
            'label' => ucfirst((string) $p->name),
        ])->values()->all();
    }

    private function digitalStatusCountsByPhase(array $filters): array
    {
        $phases = PhaseDigital::query()
            ->select(['id', 'name'])
            ->whereIn('name', ['drafting', 'review', 'approve', 'finish'])
            ->orderByRaw("CASE LOWER(name) WHEN 'drafting' THEN 1 WHEN 'review' THEN 2 WHEN 'approve' THEN 3 WHEN 'finish' THEN 4 ELSE 5 END")
            ->get();

        $baseQuery = $this->digitalInitiativesBaseQuery($filters);
        $counts = [];

        foreach ($phases as $phase) {
            $counts[(string) $phase->id] = (clone $baseQuery)
                ->whereHas('statuses', fn ($q) => $q->where('phase_id', $phase->id))
                ->count();
        }

        return $counts;
    }

    private function openDigitalInitiativesFiltered(array $filters): Collection
    {
        return $this->digitalInitiativesBaseQuery($filters)
            ->with([
                'useCase:id,name,coe_id',
                'useCase.coe:id,name',
                'source:id,name,month,year',
                'organizations:id,name,groub_id',
                'rjpps:id,name,idGoal',
                'statuses:id,name,phase_id',
                'statuses.phase:id,name',
            ])
            ->orderByDesc('id')
            ->get();
    }

    private function digitalInitiativesBaseQuery(array $filters)
    {
        return TrsDigitalInitiative::query()
            ->when($filters['search'], function ($query, $search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('useCase_description', 'like', "%{$search}%");
                    if (is_numeric($search)) {
                        $inner->orWhere('id', (int) $search);
                    }
                });
            })
            ->when($filters['category_fase'], fn ($q, $v) => $q->where('category_fase', $v))
            ->when($filters['source_id'], fn ($q, $v) => $q->where('source_id', $v))
            ->when($filters['groub_id'], fn ($q, $v) => $q->whereHas('organizations', fn ($oq) => $oq->where('groub_id', $v)))
            ->when($filters['phase_id'], fn ($q, $v) => $q->whereHas('statuses', fn ($sq) => $sq->where('phase_id', $v)))
            ->when($filters['organization_id'], fn ($q, $v) => $q->whereHas('organizations', fn ($oq) => $oq->where('trs_organization.id', $v)))
            ->when($filters['coe_id'], fn ($q, $v) => $q->whereHas('useCase', fn ($uq) => $uq->where('coe_id', $v)));
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
