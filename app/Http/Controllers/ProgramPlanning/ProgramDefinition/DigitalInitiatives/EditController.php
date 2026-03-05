<?php

namespace App\Http\Controllers\ProgramPlanning\ProgramDefinition\DigitalInitiatives;

use App\Http\Controllers\Controller;
use App\Models\DigitalInitiative;
use App\Models\InitiativeStatus;
use Inertia\Inertia;
use Inertia\Response;

class EditController extends Controller
{
    public function __invoke(DigitalInitiative $digitalInitiative): Response
    {
        $digitalInitiative->load('latestScStatusImplementation');

        return Inertia::render('ProgramPlanning/ProgramDefinition/DigitalInitiatives/Edit', [
            'initiative' => $digitalInitiative,
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
}
