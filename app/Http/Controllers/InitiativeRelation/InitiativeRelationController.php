<?php

namespace App\Http\Controllers\InitiativeRelation;

use App\Http\Controllers\Controller;
use App\Models\MstInitiative;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InitiativeRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $mstInitiatives = MstInitiative::with([
            'initiativeRelationsRow',
            'initiativeRelationsColumn',
        ])
            ->get();
        // dd($mstInitiatives->toArray());
        return Inertia::render('InitiativeRelation/InitiativeRelation', [
            'mstInitiatives' => $mstInitiatives,
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
    public function show(MstInitiative $mstInitiative)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MstInitiative $mstInitiative)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MstInitiative $mstInitiative)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MstInitiative $mstInitiative)
    {
        //
    }
}
