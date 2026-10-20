<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoRequest;
use App\Models\Evento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('eventos.manage', ['eventos' => Evento::query()->latest('data')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventoRequest $request): RedirectResponse
    {
        Evento::query()->create($request->validated());
        return redirect()->route('gestao-eventos.index')->with('success', 'Evento cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
