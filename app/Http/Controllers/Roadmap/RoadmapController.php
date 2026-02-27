<?php

namespace App\Http\Controllers\Roadmap;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Program;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoadmapController extends Controller
{
    /**
     * View-only roadmap page with all projects.
     */
    public function index(): Response
    {
        return Inertia::render('ProgramImplementation/RoadMap/Index', $this->buildRoadmapOverviewPayload());
    }


    public function add(): Response
    {
        return Inertia::render('ProgramImplementation/RoadMap/Add', $this->buildRoadmapEditorPayload(request()));
    }

    /**
     * Input/edit roadmap process page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('ProgramImplementation/RoadMap/Edit', $this->buildRoadmapEditorPayload($request));
    }

    /**
     * Display a single program with all its projects.
     */
    public function show(Program $program): Response
    {
        $program->load(['projects' => function (Builder $query): void {
            $this->applyRoadmapProjectRelations($query);
            $query->orderBy('trs_projects.id');
        }]);

        return Inertia::render('ProgramImplementation/RoadMap/Show', [
            'program' => $program,
            'milestoneTypeOptions' => Milestone::roadmapTypeOptions(),
            ...$this->roadmapYearRange(),
        ]);
    }

    /**
     * Base project relations for roadmap pages.
     */
    private function applyRoadmapProjectRelations(Builder $query): void
    {
        $query
            ->select(['trs_projects.id', 'trs_projects.name', 'trs_projects.code'])
            ->with([
                'charter' => fn ($charterQuery) => $charterQuery->select(
                    'trs_project_charters.id',
                    'trs_project_charters.project_id',
                    'trs_project_charters.objectives',
                    'trs_project_charters.duration',
                ),
                'milestones',
            ]);
    }

    /**
     * Shared roadmap year range.
     */
    private function roadmapYearRange(): array
    {
        return [
            'yearStart' => 2025,
            'yearEnd' => 2029,
        ];
    }

    /**
     * Payload for roadmap view-only page from project source table.
     */
    private function buildRoadmapOverviewPayload(): array
    {
        $projects = Project::query();
        $this->applyRoadmapProjectRelations($projects);
        $projects = $projects
            ->orderBy('trs_projects.id')
            ->get();

        return [
            'projects' => $projects,
            'milestoneTypeOptions' => Milestone::roadmapTypeOptions(),
            ...$this->roadmapYearRange(),
        ];
    }

    /**
     * Payload for roadmap input/edit page.
     */
    private function buildRoadmapEditorPayload(Request $request): array
    {
        $selectedProjectId = $request->integer('project_id');

        $projects = Project::query();
        $this->applyRoadmapProjectRelations($projects);
        $projects = $projects
            ->orderBy('trs_projects.id')
            ->get();

        $resolvedProjectId = $projects->contains('id', $selectedProjectId)
            ? $selectedProjectId
            : $projects->first()?->id;

        return [
            'projects' => $projects,
            'selectedProject' => $projects->firstWhere('id', $resolvedProjectId),
            'selectedProjectId' => $resolvedProjectId,
            'milestoneTypeOptions' => Milestone::roadmapTypeOptions(),
            ...$this->roadmapYearRange(),
        ];
    }
}
