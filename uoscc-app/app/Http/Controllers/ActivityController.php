<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Osc;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('activities.index', ['activities' => Activity::with('osc')->orderBy('data')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('activities.form', ['activity' => new Activity(['osc_id' => $request->integer('osc_id')]), 'oscs' => Osc::orderBy('nome')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request): RedirectResponse
    {
        Activity::create($request->validated());

        return redirect()->route('atividades.index')->with('success', 'Atividade cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        return view('activities.form', ['activity' => $activity, 'oscs' => Osc::orderBy('nome')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityRequest $request, Activity $activity): RedirectResponse
    {
        $activity->update($request->validated());

        return redirect()->route('atividades.index')->with('success', 'Atividade atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('atividades.index')->with('success', 'Atividade excluída com sucesso.');
    }
}
