<?php

namespace App\Http\Controllers\ProgramPlanning\ProgramDefinition\ITInitiatives;

use App\Http\Controllers\Controller;
use App\Models\InitiativeStatus;
use App\Models\MstCoe;
use App\Models\MstInitiative;
use App\Models\Project;
use App\Models\TrsOrganization;
use Illuminate\Http\RedirectResponse;
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

        $statusCounts = Project::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

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

        $masterItInitiatives = MstInitiative::query()
            ->select($masterSelectColumns)
            ->with([
                'coe:id,name',
                'organization:id,name,groub_id',
                'organization.groub:id,name',
            ])
            ->where('tipe_initiative', 2)
            ->orderBy('code')
            ->get()
            ->values();

        return Inertia::render('ProgramPlanning/ProgramDefinition/ITInitiatives/Index', [
            'totalItInitiatives' => $masterItInitiatives->count(),
            'masterItInitiatives' => $masterItInitiatives,
            'statusOptions' => $statusOptions,
            'statusCounts' => $statusCounts,
            'coeOptions' => MstCoe::orderBy('name')->get(['id', 'name'])->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values(),
            'organizationOptions' => TrsOrganization::with('groub:id,name')->orderBy('name')->get(['id', 'name', 'groub_id'])->map(fn ($o) => ['id' => $o->id, 'name' => $o->name, 'groub' => $o->groub?->name])->values(),
        ]);
    }
}
