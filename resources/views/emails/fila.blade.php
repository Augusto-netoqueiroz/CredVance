<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fila de Emails
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Tipo</th>
                            <th class="px-4 py-2 border">Email Destino</th>
                            <th class="px-4 py-2 border">Assunto</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Enviado Em</th>
                            <th class="px-4 py-2 border">Visualizado Em</th>
                            <th class="px-4 py-2 border">Tentativas</th>
                            <th class="px-4 py-2 border">Erro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emails as $email)
                            <tr>
                                <td class="border px-4 py-2">{{ $email->id }}</td>
                                <td class="border px-4 py-2">{{ $email->tipo }}</td>
                                <td class="border px-4 py-2">{{ $email->email_destino }}</td>
                                <td class="border px-4 py-2">{{ $email->assunto }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($email->status) }}</td>
                                <td class="border px-4 py-2">{{ $email->enviado_em ? $email->enviado_em->format('d/m/Y H:i') : '-' }}</td>
                                <td class="border px-4 py-2">{{ $email->visualizado_em ? $email->visualizado_em->format('d/m/Y H:i') : '-' }}</td>
                                <td class="border px-4 py-2">{{ $email->tentativas }}</td>
                                <td class="border px-4 py-2">{{ $email->erro ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $emails->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
