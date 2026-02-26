<?php

namespace App\Http\Controllers\ProgramEvaluation;

use App\Http\Controllers\Controller;
use App\Models\TrsReviewPC;
use Illuminate\Http\Request;

class TrsReviewPCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trsReviewPCs = TrsReviewPC::with('initiative')->get();

        // dd($trsReviewPCs->toArray());
        return inertia('ReviewPC/Index', [
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
        $trsReviewPC->load('initiative');

        return inertia('ReviewPC/Show', [
            'trsReviewPC' => $trsReviewPC,
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
