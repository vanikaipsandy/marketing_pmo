<?php

namespace App\Http\Controllers\MasterData\MstInitiative;

use App\Http\Controllers\Controller;
use App\Models\MstCoe;
use App\Models\MstInitiative;
use App\Models\TrsOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MstInitiativeController extends Controller
{
    /* ── Validation rules (DRY) ────────────────────── */

    private function rules(): array
    {
        return [
            'code'            => 'nullable|string|max:100',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'tipe_initiative'  => 'required|integer|in:1,2',
            'coe_id'          => 'nullable|integer|exists:mst_coe,id',
            'business_unit'   => 'nullable|integer|exists:trs_organization,id',
            'status'          => 'nullable|string|max:100',
        ];
    }

    /* ── Shared dropdown options (DRY) ─────────────── */

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

    /* ── CRUD ──────────────────────────────────────── */

    public function index(): Response
    {
        $initiatives = MstInitiative::with([
            'coe:id,name',
            'organization:id,name,groub_id',
            'organization.groub:id,name',
        ])->orderBy('code')->get();

        return Inertia::render('MasterData/MstInitiative/Index', [
            'mstInitiatives' => $initiatives,
            ...$this->dropdownOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MstInitiative::create($request->validate($this->rules()));

        return redirect()
            ->route('master-data.mst-initiatives.index')
            ->with('success', 'Master Initiative berhasil ditambahkan.');
    }

    public function update(Request $request, MstInitiative $mstInitiative): RedirectResponse
    {
        $mstInitiative->update($request->validate($this->rules()));

        return redirect()
            ->route('master-data.mst-initiatives.index')
            ->with('success', 'Master Initiative berhasil diperbarui.');
    }

    public function destroy(MstInitiative $mstInitiative): RedirectResponse
    {
        $mstInitiative->delete();

        return redirect()
            ->route('master-data.mst-initiatives.index')
            ->with('success', 'Master Initiative berhasil dihapus.');
    }
}
