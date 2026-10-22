<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupporterPortalController extends Controller
{
    public function index(Request $request): View
    {
        return view('apoiadores-painel', ['application' => $request->user()->supporterApplication]);
    }

    public function opportunities(): View
    {
        return view('apoiadores-oportunidades');
    }

    public function profile(Request $request): View
    {
        return view('apoiadores-perfil', ['application' => $request->user()->supporterApplication]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'interests' => ['required', 'string', 'min:3', 'max:500'],
        ], [
            'name.required' => 'Informe seu nome.',
            'name.string' => 'Informe um nome válido.',
            'name.min' => 'Use pelo menos 3 caracteres no nome.',
            'name.max' => 'Use no máximo 120 caracteres no nome.',
            'interests.required' => 'Informe suas áreas de interesse.',
            'interests.string' => 'Informe interesses válidos.',
            'interests.min' => 'Use pelo menos 3 caracteres nos interesses.',
            'interests.max' => 'Use no máximo 500 caracteres nos interesses.',
        ]);
        $request->user()->update(['name' => $data['name']]);
        $request->user()->supporterApplication()->update(['interests' => $data['interests']]);

        return redirect()->route('apoiadores.perfil')->with('success', 'Perfil atualizado.');
    }
}
