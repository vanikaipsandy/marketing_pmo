<?php

namespace App\Http\Controllers\ProgramPlanning\ProgramDefinition\DigitalInitiatives;

use App\Http\Controllers\Controller;
use App\Models\DigitalInitiative;
use App\Models\InitiativeStatus;
use App\Models\MstInitiative;
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

        $statusCounts = DigitalInitiative::query()
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

        $masterDigitalInitiatives = MstInitiative::query()
            ->select($masterSelectColumns)
            ->with([
                'coe:id,name',
                'organization:id,name,groub_id',
                'organization.groub:id,name',
            ])
            ->where('tipe_initiative', 1)
            ->orderBy('code')
            ->get()
            ->values();

        $initiativeItems = DigitalInitiative::query()
            ->select(['id', 'no', 'useCase'])
            ->get();

        return Inertia::render('ProgramPlanning/ProgramDefinition/DigitalInitiatives/Index', [
            'totalDigitalInitiatives' => $masterDigitalInitiatives->count(),
            'masterDigitalInitiatives' => $masterDigitalInitiatives,
            'initiativeItems' => $initiativeItems,
            'statusOptions' => $statusOptions,
            'statusCounts' => $statusCounts,
        ]);
    }
}
