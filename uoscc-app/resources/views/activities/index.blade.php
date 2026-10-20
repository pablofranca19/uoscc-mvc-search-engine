<x-layouts.app title="Atividades">
    <a href="{{ route('atividades.create') }}">Nova atividade</a>
    @foreach($activities as $activity)
        <div><strong>{{ $activity->titulo }}</strong> — {{ $activity->osc->nome }} <a href="{{ route('atividades.edit', $activity) }}">Editar</a><form method="POST" action="{{ route('atividades.destroy', $activity) }}">@csrf @method('DELETE')<button>Excluir</button></form></div>
    @endforeach
</x-layouts.app>
