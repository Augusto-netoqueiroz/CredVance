</x-app-layout>

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Enviar Boleto - Pagamento #{{ $pagamento->id }}</h1>

    @if(session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('boleto.upload', $pagamento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Arquivo PDF</label>
            <input type="file" name="boleto" accept="application/pdf" required class="border p-2 w-full">
            @error('boleto')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Fazer Upload</button>
    </form>
</div>
@endsection
</x-app-layout>