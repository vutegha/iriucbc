@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Liste des abonnés')
<div class="max-w-5xl mx-auto p-6 bg-white shadow rounded">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-800">Liste des abonnés à la newsletter</h1>
        <a href="{{ route('admin.newsletter.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Ajouter une newsletter
        </a>
    </div>

    @if(session('alert'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded">
            {!! session('alert') !!}
        </div>
    @endif

    @if($newsletters->isEmpty())
        <p class="text-gray-600">Aucune adresse enregistrée.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Email</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($newsletters as $index => $newsletter)
                        <tr>
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $newsletter->email }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('admin.newsletter.edit', $newsletter) }}"
                                   class="text-indigo-600 hover:underline">Modifier</a>

                                <form action="{{ route('admin.newsletter.destroy', $newsletter) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer cette adresse ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

