<?php

namespace App\Http\Controllers\ITInitiative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ITInitiative\CharterStoreRequest;
use App\Models\Project;
use App\Models\ProjectCharter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

class CharterController extends Controller
{
    public function store(CharterStoreRequest $request, Project $project): RedirectResponse
    {
        $validated = $request->validated();
        $versionLabel = trim((string) ($validated['version_label'] ?? ''));

        if ($versionLabel === '') {
            $versionLabel = sprintf('v%d', $project->charters()->count() + 1);
        }

        $owner = trim((string) ($validated['owner'] ?? ''));
        if ($owner === '') {
            $owner = trim((string) ($validated['owner_name'] ?? ''));
        }

        // Update project fields if provided
        $projectFields = Arr::only($validated, ['status']);
        if (!empty($projectFields)) {
            // Filter out empty values to avoid overwriting with null
            $projectFields = array_filter($projectFields, fn($v) => $v !== null && $v !== '');
            if (!empty($projectFields)) {
                $project->update($projectFields);
            }
        }

        $charterPayload = Arr::except($validated, ['version_label', 'owner_name', 'status']);
        if ($owner !== '') {
            $charterPayload['owner'] = $owner;
        } else {
            unset($charterPayload['owner']);
        }

        $project->charters()->create([
            ...$charterPayload,
            'version_label' => $versionLabel,
        ]);

        return back()->with('success', sprintf('Project Charter %s saved successfully.', $versionLabel));
    }

    public function update(CharterStoreRequest $request, Project $project, ProjectCharter $charter): RedirectResponse
    {
        // Ensure the charter belongs to this project
        abort_if($charter->project_id !== $project->id, 403, 'Charter does not belong to this project.');

        $validated = $request->validated();
        $versionLabel = trim((string) ($validated['version_label'] ?? ''));

        if ($versionLabel === '') {
            $versionLabel = $charter->version_label ?? sprintf('v%d', $project->charters()->count());
        }

        $owner = trim((string) ($validated['owner'] ?? ''));
        if ($owner === '') {
            $owner = trim((string) ($validated['owner_name'] ?? ''));
        }

        // Update project fields if provided
        $projectFields = Arr::only($validated, ['status']);
        $projectFields = array_filter($projectFields, fn($v) => $v !== null && $v !== '');
        if (!empty($projectFields)) {
            $project->update($projectFields);
        }

        $charterPayload = Arr::except($validated, ['owner_name', 'status']);
        if ($owner !== '') {
            $charterPayload['owner'] = $owner;
        } else {
            unset($charterPayload['owner']);
        }

        $charter->update([
            ...$charterPayload,
            'version_label' => $versionLabel,
        ]);

        return back()->with('success', sprintf('Project Charter %s updated successfully.', $versionLabel));
    }
}
