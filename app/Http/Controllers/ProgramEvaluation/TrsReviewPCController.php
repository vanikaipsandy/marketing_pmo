<?php

namespace App\Http\Controllers\ProgramEvaluation;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TrsReviewPC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrsReviewPCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trsReviewPCs = TrsReviewPC::with('initiative')->get();

        // dd($trsReviewPCs->toArray());
        return inertia('ProgramEvaluation/ReviewPC/Index', [
            'trsReviewPCs' => $trsReviewPCs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TrsReviewPC $trsReviewPC)
    {
        $trsReviewPC->load([
            'initiative' => fn ($query) => $query
                ->with('organization')
                ->with('initiativeRelationsRow.initiativeRow')
                ->with('initiativeRelationsRow.initiativeColumn')
                ->with('initiativeRelationsColumn.initiativeRow')
                ->with('initiativeRelationsColumn.initiativeColumn'),
        ]);

        $mappedProject = null;
        $initiativeId = $trsReviewPC->initiative_id;

        if ($initiativeId && Schema::hasTable('trs_pc_initiative')) {
            $tableColumns = Schema::getColumnListing('trs_pc_initiative');
            $projectColumn = collect(['project_id', 'trs_project_id', 'pc_id'])->first(
                static fn ($col) => in_array($col, $tableColumns, true)
            );
            $initiativeColumn = collect(['initiative_id', 'mst_initiative_id', 'useCase_id', 'use_case_id'])->first(
                static fn ($col) => in_array($col, $tableColumns, true)
            );

            if ($projectColumn && $initiativeColumn) {
                $query = DB::table('trs_pc_initiative')->where($initiativeColumn, $initiativeId);
                if (in_array('id', $tableColumns, true)) {
                    $query->orderByDesc('id');
                }
                $mappedProjectId = $query->value($projectColumn);

                if ($mappedProjectId) {
                    $mappedProject = Project::query()
                        ->with(['charter', 'owner', 'milestones', 'statusRef:id,name', 'pcStatusImplementations'])
                        ->find($mappedProjectId);
                }
            }
        }

        $reviewOptions = TrsReviewPC::query()
            ->select(['id', 'initiative_id'])
            ->with(['initiative:id,code,name'])
            ->orderBy('id')
            ->get()
            ->map(static fn (TrsReviewPC $item) => [
                'id' => (int) $item->id,
                'initiative_id' => $item->initiative_id,
                'initiative' => $item->initiative
                    ? [
                        'id' => (int) $item->initiative->id,
                        'code' => $item->initiative->code,
                        'name' => $item->initiative->name,
                    ]
                    : null,
            ])
            ->values();

        return inertia('ProgramEvaluation/ReviewPC/Show', [
            'trsReviewPC' => $trsReviewPC,
            'reviewOptions' => $reviewOptions,
            'mappedProject' => $mappedProject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrsReviewPC $trsReviewPC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrsReviewPC $trsReviewPC)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrsReviewPC $trsReviewPC)
    {
        //
    }
}
