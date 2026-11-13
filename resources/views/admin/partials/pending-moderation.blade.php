{{--
    Liste des contenus en attente de modération (non publiés).

    Paramètres :
      $items          paginator des éléments
      $heading        titre de la page, ex. "Rapports en attente"
      $itemLabel      nom au pluriel, ex. "rapports"
      $routePrefix    préfixe des routes, ex. "admin.rapports"
      $titleField     attribut affiché comme titre, ex. "titre"
      $publishAbility capacité de la policy pour publier, ex. "publish"
      $meta           (facultatif) fonction ($item) => texte secondaire
      $showRoute      (facultatif) nom de la route d'affichage
--}}
@php
    $showRoute = $showRoute ?? $routePrefix . '.show';
    $meta = $meta ?? null;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $heading }}</h1>
            <p class="text-gray-600">
                {{ $items->total() }} {{ $itemLabel }} non publié(s), du plus récent au plus ancien.
            </p>
        </div>
        <a href="{{ route($routePrefix . '.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($items->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <p class="text-lg font-semibold text-gray-900">Rien à modérer</p>
                <p class="text-sm text-gray-600 mt-1">Tous les {{ $itemLabel }} sont publiés.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Créé le</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $item->{$titleField} ?: 'Sans titre' }}</p>
                                    @if($meta && ($metaText = $meta($item)))
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $metaText }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ optional($item->created_at)->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('view', $item)
                                            <a href="{{ route($showRoute, $item) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-1.5"></i>Voir
                                            </a>
                                        @endcan
                                        @can('update', $item)
                                            <a href="{{ route($routePrefix . '.edit', $item) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-pen mr-1.5"></i>Modifier
                                            </a>
                                        @endcan
                                        @can($publishAbility, $item)
                                            <form action="{{ route($routePrefix . '.publish', $item) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                                                    <i class="fas fa-check mr-1.5"></i>Publier
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $items->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
