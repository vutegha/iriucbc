<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::latest()->paginate(15);
        return view('admin.categorie.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categorie.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string|max:1000',
        ]);

        Categorie::create($validated);

        return redirect()->route('admin.categorie.index')
            ->with('alert', '<span class="alert alert-success">Catégorie créée avec succès.</span>');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categorie.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categorie->id)],
            'description' => 'nullable|string|max:1000',
        ]);

        $categorie->update($validated);

        return redirect()->route('admin.categorie.index')
            ->with('alert', '<span class="alert alert-success">Catégorie mise à jour avec succès.</span>');
    }

    public function destroy(Categorie $categorie)
    {
        try {
            $categorie->delete();

            return redirect()->route('admin.categorie.index')
                ->with('alert', '<span class="alert alert-success">Catégorie supprimée avec succès.</span>');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }
}
