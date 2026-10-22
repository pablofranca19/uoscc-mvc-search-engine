@extends('layouts.app')
@section('title', 'Reservas — '.$espaco->name.' — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20">
    <p class="eyebrow">Solicitações de reserva</p>
    <h1 class="section-title">Reservas de {{ $espaco->name }}</h1>
    <p class="section-copy">Acompanhe todas as solicitações feitas para este espaço e a situação de cada uma.</p>
    <p class="mt-4 text-sm font-semibold text-slate-600" role="status" aria-live="polite">{{ count($reservas) }} solicitações encontradas</p>

    @if(count($reservas) === 0)
        <div class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
            <h2 class="text-xl font-bold">Nenhuma reserva registrada</h2>
            <p class="mt-3 text-slate-500">Ainda não há solicitações de reserva para este espaço.</p>
        </div>
    @else
        <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-4">Solicitante</th>
                        <th scope="col" class="px-5 py-4">Contato</th>
                        <th scope="col" class="px-5 py-4">Data desejada</th>
                        <th scope="col" class="px-5 py-4">Finalidade</th>
                        <th scope="col" class="px-5 py-4">Situação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reservas as $reserva)
                        @php
                            $badge = match ($reserva->status) {
                                \App\Enums\ReservationStatus::Approved => 'bg-emerald-100 text-emerald-800',
                                \App\Enums\ReservationStatus::Rejected => 'bg-rose-100 text-rose-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $reserva->requester_name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $reserva->requester_contact }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $reserva->requested_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $reserva->purpose }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ $reserva->status->label() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a href="{{ route('espacos.show', $espaco->id) }}" class="mt-8 inline-flex rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100">Voltar ao espaço</a>
</section>
@endsection
