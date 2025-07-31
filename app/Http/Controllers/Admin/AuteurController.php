<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auteur;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuteurController extends Controller
{
    public function index()
    {
        $auteurs = Auteur::paginate(20);
        return view('admin.auteur.index', compact('auteurs'));
    }

    public function create()
    {
        return view('admin.auteur.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:auteurs,email',
                'institution' => 'nullable|string|max:255',
                'biographie' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
            ]);

            if ($request->hasFile('photo')) {
                $filename = uniqid('auteur_') . '.' . $request->file('photo')->getClientOriginalExtension();
                $path = $request->file('photo')->storeAs('assets/auteurs', $filename, 'public');
                $validated['photo'] = $path;
            }

            $auteur = Auteur::create($validated);

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'auteur' => [
                        'id' => $auteur->id,
                        'nom' => $auteur->nom,
                        'prenom' => $auteur->prenom,
                        'nom_complet' => $auteur->prenom ? "{$auteur->prenom} {$auteur->nom}" : $auteur->nom,
                        'email' => $auteur->email,
                        'institution' => $auteur->institution,
                    ],
                    'message' => 'Auteur créé avec succès!'
                ]);
            }

            return redirect()->route('admin.auteur.index')
                ->with('alert', '<span class="alert alert-success">Auteur enregistré avec succès.</span>');
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation.</span>');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'auteur: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Auteur $auteur)
    {
        return view('admin.auteur.edit', compact('auteur'));
    }

    public function update(Request $request, Auteur $auteur)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:auteurs,email,' . $auteur->id,
                'institution' => 'nullable|string|max:255',
                'biographie' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
            ]);

            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($auteur->photo && Storage::disk('public')->exists($auteur->photo)) {
                    Storage::disk('public')->delete($auteur->photo);
                }

                $filename = uniqid('auteur_') . '.' . $request->file('photo')->getClientOriginalExtension();
                $path = $request->file('photo')->storeAs('assets/auteurs', $filename, 'public');
                $validated['photo'] = $path;
            }

            $auteur->update($validated);

            return redirect()->route('admin.auteur.index')
                ->with('alert', '<span class="alert alert-success">Auteur mis à jour avec succès.</span>');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation.</span>');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function destroy(Auteur $auteur)
    {
        try {
            if ($auteur->photo && Storage::disk('public')->exists($auteur->photo)) {
                Storage::disk('public')->delete($auteur->photo);
            }

            $auteur->delete();

            return redirect()->route('admin.auteur.index')
                ->with('alert', '<span class="alert alert-success">Auteur supprimé avec succès.</span>');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur de suppression : ' . e($e->getMessage()) . '</span>');
        }
    }
    public function show(Auteur $auteur)
    {
        return view('admin.auteur.show', compact('auteur'));
    }

    /**
     * Recherche d'auteurs pour AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $auteurs = Auteur::where(function($q) use ($query) {
            $q->where('nom', 'LIKE', "%{$query}%")
              ->orWhere('prenom', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('institution', 'LIKE', "%{$query}%");
        })
        ->select('id', 'nom', 'prenom', 'email', 'institution')
        ->limit(20)
        ->get()
        ->map(function($auteur) {
            return [
                'id' => $auteur->id,
                'nom' => $auteur->prenom ? "{$auteur->prenom} {$auteur->nom}" : $auteur->nom,
                'email' => $auteur->email,
                'institution' => $auteur->institution
            ];
        });

        return response()->json($auteurs);
    }

    /**
     * Création d'auteur via AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:auteurs,email',
                'institution' => 'nullable|string|max:255',
            ]);

            $auteur = Auteur::create($validated);

            return response()->json([
                'success' => true,
                'auteur' => [
                    'id' => $auteur->id,
                    'nom' => $auteur->nom,
                    'prenom' => $auteur->prenom,
                    'nom_complet' => $auteur->prenom ? "{$auteur->prenom} {$auteur->nom}" : $auteur->nom,
                ],
                'message' => 'Auteur créé avec succès!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'auteur'
            ], 500);
        }
    }
}