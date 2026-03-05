<?php

namespace App\Http\Controllers\MasterData\MstInitiative;

use App\Http\Controllers\Controller;
use App\Models\MstCoe;
use App\Models\MstInitiative;
use App\Models\StatusMstInitiative;
use App\Models\TrsOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MstInitiativeController extends Controller
{
    /* ── Validation ────────────────────────────────── */

    private function rules(): array
    {
        return [
            'code'             => 'nullable|string|max:100',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'tipe_initiative'  => 'required|integer|in:1,2',
            'coe_id'           => 'nullable|integer|exists:mst_coe,id',
            'business_unit'    => 'nullable|integer|exists:trs_organization,id',
            'status'           => 'nullable|string|max:255',
        ];
    }

    private function statusRules(): array
    {
        return [
            'status'  => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'notes'   => 'nullable|string|max:255',
        ];
    }

    /* ── Dropdown options (DRY) ────────────────────── */

    private function dropdownOptions(): array
    {
        return [
            'coeOptions' => MstCoe::orderBy('name')
                ->get(['id', 'name'])
                ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])
                ->values(),

            'organizationOptions' => TrsOrganization::with('groub:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'groub_id'])
                ->map(fn ($o) => [
                    'id'    => $o->id,
                    'name'  => $o->name,
                    'groub' => $o->groub?->name,
                ])
                ->values(),

            'tipeOptions' => [
                ['id' => 1, 'label' => 'Digital Initiative'],
                ['id' => 2, 'label' => 'IT Initiative'],
            ],
        ];
    }

    /* ── Initiative CRUD ──────────────────────────── */

    public function index(): Response
    {
        $initiatives = MstInitiative::with([
            'coe:id,name',
            'organization:id,name,groub_id',
            'organization.groub:id,name',
            'latestStatus',
        ])->orderBy('code')->get();

        return Inertia::render('MasterData/MstInitiative/Index', [
            'mstInitiatives' => $initiatives,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('MasterData/MstInitiative/Create', $this->dropdownOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['status'] = $validated['status'] ?? 'drafting';
        $initiative = MstInitiative::create($validated);

        // Auto-create initial status history entry
        $initiative->statusHistory()->create([
            'status'  => $validated['status'],
            'tanggal' => now(),
            'notes'   => null,
        ]);

        // Save additional statuses if provided (from Create page)
        foreach ($request->input('statuses', []) as $s) {
            if (!empty($s['status'])) {
                $initiative->statusHistory()->create([
                    'status'  => $s['status'],
                    'tanggal' => $s['tanggal'] ?? null,
                    'notes'   => $s['notes'] ?? null,
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Master Initiative berhasil ditambahkan.');
    }

    public function edit(MstInitiative $mstInitiative): Response
    {
        $mstInitiative->load([
            'coe:id,name',
            'organization:id,name,groub_id',
            'organization.groub:id,name',
            'statusHistory' => fn ($q) => $q->orderByDesc('id'),
        ]);

        return Inertia::render('MasterData/MstInitiative/Edit', [
            'initiative' => $mstInitiative,
            ...$this->dropdownOptions(),
        ]);
    }

    public function update(Request $request, MstInitiative $mstInitiative): RedirectResponse
    {
        $mstInitiative->update($request->validate($this->rules()));

        return redirect()
            ->route('master-data.mst-initiatives.edit', $mstInitiative)
            ->with('success', 'Master Initiative berhasil diperbarui.');
    }

    public function destroy(MstInitiative $mstInitiative): RedirectResponse
    {
        $mstInitiative->delete();

        return redirect()
            ->route('master-data.mst-initiatives.index')
            ->with('success', 'Master Initiative berhasil dihapus.');
    }

    /* ── Status History CRUD ──────────────────────── */

    public function storeStatus(Request $request, MstInitiative $mstInitiative): RedirectResponse
    {
        $mstInitiative->statusHistory()->create($request->validate($this->statusRules()));

        return redirect()
            ->route('master-data.mst-initiatives.edit', $mstInitiative)
            ->with('success', 'Status berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, StatusMstInitiative $status): RedirectResponse
    {
        $status->update($request->validate($this->statusRules()));

        return redirect()
            ->route('master-data.mst-initiatives.edit', $status->initiative_id)
            ->with('success', 'Status berhasil diperbarui.');
    }

    public function destroyStatus(StatusMstInitiative $status): RedirectResponse
    {
        $initiativeId = $status->initiative_id;
        $status->delete();

        return redirect()
            ->route('master-data.mst-initiatives.edit', $initiativeId)
            ->with('success', 'Status berhasil dihapus.');
    }
}
