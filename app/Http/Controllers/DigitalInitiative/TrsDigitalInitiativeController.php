<?php

namespace App\Http\Controllers\DigitalInitiative;

use App\Http\Controllers\Controller;
use App\Models\Coe;
use App\Models\DataSource;
use App\Models\Organization;
use App\Models\PhaseDigital;
use App\Models\StatusDigital;
use App\Models\Theme;
use App\Models\TrsDigitalInitiative;
use App\Models\UseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TrsDigitalInitiativeController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'category_fase' => $request->input('category_fase'),
            'source_id' => $request->input('source_id'),
            'phase_id' => $request->input('phase_id'),
            'organization_id' => $request->input('organization_id'),
            'coe_id' => $request->input('coe_id'),
        ];

        $initiatives = TrsDigitalInitiative::query()
            ->with([
                'useCase:id,name,coe_id',
                'useCase.coe:id,name',
                'source:id,name,month,year',
                'organizations:id,name,groub_id',
                'rjpps:id,name,idGoal',
                'statuses:id,name,phase_id',
                'statuses.phase:id,name',
            ])
            ->when($filters['search'], function ($query, $search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner
                        ->where('useCase_description', 'like', "%{$search}%");

                    if (is_numeric($search)) {
                        $inner->orWhere('id', (int) $search);
                    }
                });
            })
            ->when($filters['category_fase'], fn ($query, $categoryFase) => $query->where('category_fase', $categoryFase))
            ->when($filters['source_id'], fn ($query, $sourceId) => $query->where('source_id', $sourceId))
            ->when($filters['phase_id'], fn ($query, $phaseId) => $query->whereHas('statuses', fn ($statusQuery) => $statusQuery->where('trs_status_digital.phase_id', $phaseId)))
            ->when($filters['organization_id'], fn ($query, $organizationId) => $query->whereHas('organizations', fn ($organizationQuery) => $organizationQuery->where('trs_organization.id', $organizationId)))
            ->when($filters['coe_id'], fn ($query, $coeId) => $query->whereHas('useCase', fn ($useCaseQuery) => $useCaseQuery->where('coe_id', $coeId)))
            ->orderByDesc('id')
            ->get();

        return Inertia::render('DigitalInitiativeV2/Index', [
            'initiatives' => $initiatives,
            'filters' => $filters,
            'options' => $this->options(),
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('DigitalInitiativeV2/Create', [
            'options' => $this->options(),
            'categoryOptions' => $this->categoryOptions(),
            'planningReferences' => $this->planningReferences(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated): void {
            $initiative = TrsDigitalInitiative::create([
                'category_fase' => (int) $validated['category_fase'],
                'ref_id' => $validated['ref_id'] ?? null,
                'useCase_id' => (int) $validated['useCase_id'],
                'useCase_description' => $validated['useCase_description'],
                'value' => (int) $validated['value'],
                'urgency' => (int) $validated['urgency'],
                'source_id' => (int) $validated['source_id'],
            ]);

            $initiative->organizations()->sync($validated['organization_ids'] ?? []);
            $initiative->statuses()->sync($validated['status_ids'] ?? []);
            $initiative->rjpps()->sync($validated['rjpp_ids'] ?? []);
        });

        return redirect()->route('digital-initiatives.index')->with('success', 'Digital initiative created successfully.');
    }

    public function show(TrsDigitalInitiative $digitalInitiative): Response
    {
        $digitalInitiative->load([
            'planningOrImplementation:id,useCase_description,category_fase',
            'useCase:id,name,coe_id',
            'useCase.coe:id,name',
            'source:id,name,month,year',
            'organizations:id,name,groub_id',
            'organizations.groub:id,name',
            'rjpps:id,name,idGoal',
            'rjpps.goal:id,title',
            'statuses:id,name,phase_id',
            'statuses.phase:id,name',
            'digitalDetail',
        ]);

        return Inertia::render('DigitalInitiativeV2/Show', [
            'initiative' => $digitalInitiative,
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function edit(TrsDigitalInitiative $digitalInitiative): Response
    {
        $digitalInitiative->load(['organizations:id', 'rjpps:id', 'statuses:id']);

        return Inertia::render('DigitalInitiativeV2/Edit', [
            'initiative' => [
                'id' => $digitalInitiative->id,
                'category_fase' => $digitalInitiative->category_fase,
                'ref_id' => $digitalInitiative->ref_id,
                'useCase_id' => $digitalInitiative->useCase_id,
                'useCase_description' => $digitalInitiative->useCase_description,
                'value' => $digitalInitiative->value,
                'urgency' => $digitalInitiative->urgency,
                'source_id' => $digitalInitiative->source_id,
                'organization_ids' => $digitalInitiative->organizations->pluck('id')->values(),
                'rjpp_ids' => $digitalInitiative->rjpps->pluck('id')->values(),
                'status_ids' => $digitalInitiative->statuses->pluck('id')->values(),
            ],
            'options' => $this->options(),
            'categoryOptions' => $this->categoryOptions(),
            'planningReferences' => $this->planningReferences($digitalInitiative->id),
        ]);
    }

    public function update(Request $request, TrsDigitalInitiative $digitalInitiative): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated, $digitalInitiative): void {
            $digitalInitiative->update([
                'category_fase' => (int) $validated['category_fase'],
                'ref_id' => $validated['ref_id'] ?? null,
                'useCase_id' => (int) $validated['useCase_id'],
                'useCase_description' => $validated['useCase_description'],
                'value' => (int) $validated['value'],
                'urgency' => (int) $validated['urgency'],
                'source_id' => (int) $validated['source_id'],
            ]);

            $digitalInitiative->organizations()->sync($validated['organization_ids'] ?? []);
            $digitalInitiative->statuses()->sync($validated['status_ids'] ?? []);
            $digitalInitiative->rjpps()->sync($validated['rjpp_ids'] ?? []);
        });

        return redirect()->route('digital-initiatives.index')->with('success', 'Digital initiative updated successfully.');
    }

    public function destroy(TrsDigitalInitiative $digitalInitiative): RedirectResponse
    {
        DB::transaction(function () use ($digitalInitiative): void {
            $digitalInitiative->organizations()->detach();
            $digitalInitiative->statuses()->detach();
            $digitalInitiative->rjpps()->detach();
            $digitalInitiative->digitalDetail()->delete();
            $digitalInitiative->delete();
        });

        return redirect()->route('digital-initiatives.index')->with('success', 'Digital initiative deleted successfully.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'category_fase' => ['required', 'integer', 'in:1,2'],
            'ref_id' => ['nullable', 'integer', 'exists:trs_digital_initiative,id'],
            'useCase_id' => ['required', 'integer', 'exists:trs_use_case,id'],
            'useCase_description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'integer', 'in:1,2,3,4'],
            'urgency' => ['required', 'integer', 'in:1,2,3,4'],
            'source_id' => ['required', 'integer', 'exists:mst_data_source,id'],
            'organization_ids' => ['nullable', 'array'],
            'organization_ids.*' => ['integer', 'exists:trs_organization,id'],
            'status_ids' => ['nullable', 'array'],
            'status_ids.*' => ['integer', 'exists:trs_status_digital,id'],
            'rjpp_ids' => ['nullable', 'array'],
            'rjpp_ids.*' => ['integer', 'exists:trs_themes,id'],
        ]);
    }

    private function options(): array
    {
        $phaseOrder = [
            'propose' => 1,
            'review' => 2,
            'approve' => 3,
            'finish' => 4,
        ];

        $statusOrderByPhase = [
            'propose' => [
                'drafting' => 1,
                'submitted' => 2,
                'canceled' => 3,
            ],
            'review' => [
                'on review' => 1,
                'perlu perbaikan' => 2,
                'hold' => 3,
                'reject' => 4,
                'accepted' => 5,
            ],
            'approve' => [
                'on process' => 1,
                'accept' => 2,
                'reject' => 3,
            ],
            'finish' => [
                'baseline' => 1,
                'hold' => 2,
            ],
        ];

        $statuses = StatusDigital::query()
            ->with('phase:id,name')
            ->select(['id', 'name', 'phase_id'])
            ->whereHas('phase', fn ($query) => $query->whereIn('name', array_keys($phaseOrder)))
            ->get()
            ->sortBy(function (StatusDigital $status) use ($phaseOrder, $statusOrderByPhase): array {
                $phaseName = strtolower((string) ($status->phase?->name ?? ''));
                $statusName = strtolower((string) $status->name);
                $statusOrder = $statusOrderByPhase[$phaseName][$statusName] ?? 999;

                return [
                    $phaseOrder[$phaseName] ?? 999,
                    $statusOrder,
                    $status->id,
                ];
            })
            ->values();

        $phases = PhaseDigital::query()
            ->select(['id', 'name'])
            ->whereIn('name', array_keys($phaseOrder))
            ->get()
            ->sortBy(function (PhaseDigital $phase) use ($phaseOrder): array {
                $phaseName = strtolower((string) $phase->name);

                return [
                    $phaseOrder[$phaseName] ?? 999,
                    $phase->id,
                ];
            })
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
            ->map(fn (Coe $coe) => [
                'id' => $coe->id,
                'name' => $coe->name,
            ]);

        if ($coes->isEmpty()) {
            $coes = Coe::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get();
        }

        return [
            'useCases' => UseCase::query()->select(['id', 'name'])->orderBy('name')->get(),
            'sources' => DataSource::query()->select(['id', 'name', 'month', 'year'])->orderBy('year')->orderBy('month')->get(),
            'organizations' => Organization::query()->select(['id', 'name'])->orderBy('name')->get(),
            'coes' => $coes,
            'statuses' => $statuses,
            'phases' => $phases,
            'rjpps' => Theme::query()->select(['id', 'name'])->orderBy('name')->get(),
        ];
    }

    private function planningReferences(?int $exceptId = null)
    {
        return TrsDigitalInitiative::query()
            ->select(['id', 'useCase_description'])
            ->where('category_fase', 1)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->orderByDesc('id')
            ->get();
    }

    private function categoryOptions(): array
    {
        return [
            ['id' => 1, 'label' => 'Planning / Scope Charter'],
            ['id' => 2, 'label' => 'Implementation / Project Charter'],
        ];
    }
}
