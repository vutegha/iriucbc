@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">media</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 bg-gray-100 ">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">MÃ©diathÃ¨que</h2>
        <a href="{{ route('admin.media.create') }}"
           class="bg-iri-primary hover:bg-iri-secondary text-white px-4 py-2 rounded shadow">
            + Ajouter un mÃ©dia
        </a>
    </div>

    {{-- Filtrage par type --}}
    <div class="mb-6">
        <form method="GET" class="flex space-x-4">
            <select name="type" onchange="this.form.submit()" class="border rounded px-3 py-2">
                <option value="">Tous les types</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>VidÃ©os</option>
            </select>
        </form>
    </div>

    {{-- Grille de mÃ©dias --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($medias as $item)
            <div class="relative group">
                <div class="relative h-48 bg-gray-100 rounded overflow-hidden shadow">
                    @if ($item->type === 'image')
                        <img src="{{ asset('storage/' . $item->medias) }}" alt="{{ $item->titre }}" class="object-cover w-full h-full">
                    @elseif ($item->type === 'video')
                        <video class="w-full h-full object-cover" muted>
                            <source src="{{ asset('storage/' . $item->medias) }}" type="video/mp4">
                        </video>
                    @endif
                </div>
                <button onclick="openModal('{{ asset('storage/' . $item->medias) }}', '{{ $item->titre }}', '{{ route('admin.media.edit', $item) }}', '{{ route('admin.media.destroy', $item) }}')"
                        class="absolute inset-0 flex items-start justify-center bg-black bg-opacity-40 hover:bg-opacity-60 transition-opacity">
                    <span class="text-white text-sm font-semibold p-2 bg-black bg-opacity-50 rounded">
                        {{ $item->titre }} <br>
                        <span class="text-xs">{{ $item->created_at->format('d/m/Y') }}</span>
                    </span>
                </button>
            </div>
        @endforeach
    </div>

    {{-- Modal --}}
    <div id="mediaModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded shadow-lg max-w-3xl w-full p-6 relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 hover:text-red-600 text-xl">&times;</button>

            <h3 id="modalTitle" class="text-xl font-semibold mb-4"></h3>
            <div id="modalContent" class="mb-4"></div>

            <div class="flex justify-between items-center mt-6">
                <div class="flex gap-4">
                    <a id="modalEditBtn" href="#" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Modifier
                    </a>
                    <form id="modalDeleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                onclick="return confirm('Supprimer ce mÃ©dia ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
                <a id="modalDownload" href="#" download
                   class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">
                    TÃ©lÃ©charger
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function openModal(fileUrl, title, editUrl, deleteUrl) {
        document.getElementById('mediaModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = title;

        const ext = fileUrl.split('.').pop().toLowerCase();
        let content = '';

        if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(ext)) {
            content = `<img src="${fileUrl}" class="w-full rounded">`;
        } else if (['mp4', 'webm', 'mov'].includes(ext)) {
            content = `<video controls class="w-full rounded"><source src="${fileUrl}" type="video/mp4">Votre navigateur ne prend pas en charge la vidÃ©o.</video>`;
        } else {
            content = `<p>Fichier non supportÃ©.</p>`;
        }

        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('modalEditBtn').href = editUrl;
        document.getElementById('modalDeleteForm').action = deleteUrl;
        document.getElementById('modalDownload').href = fileUrl;
    }

    function closeModal() {
        document.getElementById('mediaModal').classList.add('hidden');
    }
</script>

@endsection



