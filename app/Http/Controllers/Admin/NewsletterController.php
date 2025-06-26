<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(20);
        return view('admin.newsletter.index', compact('newsletters'));
    }

    public function create()
    {
        return view('admin.newsletter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:newsletters,email',
        ]);

        Newsletter::create($validated);

        return redirect()->route('admin.newsletter.index')
            ->with('alert', '<span class="alert alert-success">Email inscrit avec succès.</span>');
    }

    public function edit(Newsletter $newsletter)
    {
        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('newsletters')->ignore($newsletter->id)],
        ]);

        $newsletter->update($validated);

        return redirect()->route('admin.newsletter.index')
            ->with('alert', '<span class="alert alert-success">Email mis à jour avec succès.</span>');
    }

    public function destroy(Newsletter $newsletter)
    {
        try {
            $newsletter->delete();

            return redirect()->route('admin.newsletter.index')
                ->with('alert', '<span class="alert alert-success">Inscription supprimée avec succès.</span>');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }
}
