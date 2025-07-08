<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalhes do Email #{{ $email->id }}</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
        <p><strong>Destinatário:</strong> {{ $email->email_destino }}</p>
        <p><strong>Assunto:</strong> {{ $email->assunto }}</p>
        <p><strong>Status:</strong> {{ ucfirst($email->status) }}</p>
        <p><strong>Enviado em:</strong> {{ $email->enviado_em?->format('d/m/Y H:i') ?? '-' }}</p>
        <p><strong>Visualizado em:</strong> {{ $email->visualizado_em?->format('d/m/Y H:i') ?? '-' }}</p>

        <h3 class="mt-4 font-semibold text-lg">Conteúdo do Email</h3>
        <div class="border rounded p-4 bg-gray-50 mb-4">
            {!! $email->corpo_customizado ?? nl2br(e($email->template->corpo_html ?? '')) !!}
        </div>

        <h3 class="mt-4 font-semibold text-lg">Histórico de Tracking</h3>
        @if($email->trackings->isEmpty())
            <p>Sem eventos registrados.</p>
        @else
            <ul class="list-disc pl-5">
                @foreach($email->trackings as $tracking)
                    <li>
                        {{ ucfirst($tracking->tipo_evento) }} em {{ $tracking->created_at->format('d/m/Y H:i') }} - IP: {{ $tracking->ip }} - User Agent: {{ $tracking->user_agent }}
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('emails.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Voltar</a>

            <form action="{{ route('emails.destroy', $email) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este email?')" >
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Excluir</button>
            </form>
        </div>
    </div>
</x-app-layout>
