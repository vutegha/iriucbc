<?php  

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($request->hasFile('icone')) {
            $validated['icone'] = $request->file('icone')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service ajouté.');
    }

    public function edit(Service $service)
    {
        return view('admin.service.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($request->hasFile('icone')) {
            if ($service->icone) {
                Storage::disk('public')->delete($service->icone);
            }
            $validated['icone'] = $request->file('icone')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        if ($service->icone) {
            Storage::disk('public')->delete($service->icone);
        }
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'Service supprimé.');
    }
}
