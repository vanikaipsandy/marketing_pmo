<?php

namespace App\Http\Controllers\ITInitiative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ITInitiative\ITInitiativeIndexRequest;
use App\Http\Requests\ITInitiative\ITInitiativeStoreRequest;
use App\Http\Requests\ITInitiative\ITInitiativeUpdateRequest;
use App\Models\InitiativeStatus;
use App\Models\MstInitiative;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ITInitiativeController extends Controller
{
    public function roadmapIndex(Request $request): Response
    {
        $selectedProjectId = $request->integer('project_id');

        $roadmapScope = static function ($query): void {
            $query->where(function ($builder): void {
                $builder
                    ->whereHas('charter', static function ($charter): void {
                        $charter
                            ->whereNotNull('duration')
                            ->where('duration', '!=', '');
                    })
                    ->orWhereHas('milestones', static function ($milestones): void {
                        $milestones
                            ->whereNotNull('start_date')
                            ->orWhereNotNull('end_date');
                    });
            });
        };

        $projects = Project::query()
            ->select(['id', 'name', 'code'])
            ->where($roadmapScope)
            ->orderBy('name')
            ->get();

        $resolvedProjectId = $projects->contains('id', $selectedProjectId)
            ? $selectedProjectId
            : $projects->first()?->id;

        $selectedProject = $resolvedProjectId
            ? Project::query()
                ->with(['charter', 'milestones', 'owner'])
                ->find($resolvedProjectId)
            : null;

        return Inertia::render('Roadmap/Index', [
            'itInitiatives' => $projects,
            'selectedProject' => $selectedProject,
            'selectedProjectId' => $resolvedProjectId,
        ]);
    }

    public function index(ITInitiativeIndexRequest $request): Response
    {
        $filters = $request->validated();
        $statusOptions = InitiativeStatus::ordered()
            ->map(fn (InitiativeStatus $status) => [
                'id' => (int) $status->id,
                'name' => $status->name,
                'label' => ucfirst($status->name),
            ])
            ->values();

        $baselineStatus = $statusOptions->firstWhere('name', 'baseline');
        $baselineStatusId = (int) ($baselineStatus['id'] ?? InitiativeStatus::baselineId());
        $filterStatus = $filters['status'] ?? null;

        $projects = Project::query()
            ->with(['owner', 'charter', 'statusRef:id,name', 'pcStatusImplementations'])
            ->when($filterStatus, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(function ($inner) use ($search): void {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%");
            }))
            ->when($filters['month'] ?? null, fn ($q, $month) => $q->whereMonth('updated_at', $month))
            ->orderBy('id', 'asc')
            ->get();

        $masterItInitiatives = MstInitiative::query()
            ->select([
                'id',
                'coe_id',
                'tipe_initiative',
                'business_unit',
                'code',
                'name',
                'description',
                'status',
            ])
            ->with([
                'coe:id,name',
                'organization:id,name,groub_id',
                'organization.groub:id,name',
            ])
            ->where('tipe_initiative', 2)
            ->orderBy('code')
            ->get()
            ->values();

        $totalItInitiatives = $masterItInitiatives->count();

        $statusCountsRaw = Project::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return Inertia::render('ITInitiative/Index', [
            'itInitiatives' => $projects,
            'statusOptions' => $statusOptions,
            'completedStatusId' => $baselineStatusId,
            'totalItInitiatives' => $totalItInitiatives,
            'masterItInitiatives' => $masterItInitiatives,
            'statusCounts' => $statusCountsRaw,
            'filters'  => [
                'search' => $filters['search'] ?? null,
                'status' => $filterStatus,
                'month' => $filters['month'] ?? null,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ITInitiative/Create', [
            'statusOptions' => InitiativeStatus::ordered()
                ->map(fn (InitiativeStatus $status) => [
                    'id' => (int) $status->id,
                    'name' => $status->name,
                    'label' => ucfirst($status->name),
                ])
                ->values(),
            'defaultStatusId' => InitiativeStatus::DRAFTING,
        ]);
    }

    public function store(ITInitiativeStoreRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated());

        if ($project->status) {
            $statusModel = InitiativeStatus::find($project->status);
            $statusName = $statusModel ? $statusModel->name : (string)$project->status;
            
            \App\Models\PcStatusImplementation::create([
                'project_id' => $project->id,
                'status' => $statusName,
                'date' => now()->toDateString(),
                'time_start' => now()->toTimeString(),
            ]);
        }

        return redirect()->route('it-initiatives.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project): Response
    {
        $project->load([
            'charter',
            'charters' => static fn ($query) => $query->latest(),
            'milestones',
            'programs',
            'goals',
            'owner',
            'statusRef:id,name',
        ]);

        return Inertia::render('ITInitiative/Show', [
            'itInitiative' => $project,
        ]);
    }

    public function edit(Project $project): Response
    {
        $project->load(['pcStatusImplementations']);
        
        return Inertia::render('ITInitiative/Edit', [
            'itInitiative' => $project,
            'statusOptions' => InitiativeStatus::ordered()
                ->map(fn (InitiativeStatus $status) => [
                    'id' => (int) $status->id,
                    'name' => $status->name,
                    'label' => ucfirst($status->name),
                ])
                ->values(),
            'defaultStatusId' => InitiativeStatus::DRAFTING,
        ]);
    }

    public function update(ITInitiativeUpdateRequest $request, Project $project): RedirectResponse
    {
        $oldStatus = $project->status;
        $project->update($request->validated());

        if ((string)$project->status !== (string)$oldStatus || !$project->wasRecentlyCreated) {
            $statusModel = InitiativeStatus::find($project->status);
            $statusName = $statusModel ? $statusModel->name : (string)$project->status;
            
            \App\Models\PcStatusImplementation::create([
                'project_id' => $project->id,
                'status' => $statusName,
                'date' => now()->toDateString(),
                'time_start' => now()->toTimeString(),
            ]);
        }

        return redirect()->route('it-initiatives.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('it-initiatives.index')->with('success', 'Project deleted successfully.');
    }

    public function storeImplementationStatus(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'month_year' => 'required|date_format:Y-m',
        ]);

        \App\Models\PcStatusImplementation::create([
            'project_id' => $project->id,
            'status' => $validated['status'],
            'date' => \Carbon\Carbon::createFromFormat('Y-m', $validated['month_year'])->startOfMonth()->toDateString(),
            'time_start' => now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Status added successfully.');
    }

    public function destroyImplementationStatus($id): RedirectResponse
    {
        $status = \App\Models\PcStatusImplementation::findOrFail($id);
        $status->delete();

        return redirect()->back()->with('success', 'Status deleted successfully.');
    }
}
