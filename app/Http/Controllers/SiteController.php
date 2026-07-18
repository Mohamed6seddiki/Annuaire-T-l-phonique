<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $items = Site::withCount('standards')->orderBy('libelle')->get();
        return view('ref.index', [
            'title' => 'Sites',
            'items' => $items,
            'fields' => ['libelle', 'libelle_arb'],
            'route' => 'sites',
        ]);
    }

    public function create()
    {
        return view('ref.form', [
            'title' => 'Nouveau site',
            'route' => 'sites',
            'fields' => [
                ['name' => 'libelle', 'label' => 'Libellé', 'type' => 'text'],
                ['name' => 'libelle_arb', 'label' => 'Libellé (Arabe)', 'type' => 'text'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);
        Site::create($data);
        return redirect()->route('sites.index')->with('success', 'Site créé.');
    }

    public function show(Site $site)
    {
        return redirect()->route('referentiel.index');
    }

    public function edit(Site $site)
    {
        return view('ref.form', [
            'title' => 'Modifier le site',
            'route' => 'sites',
            'item' => $site,
            'fields' => [
                ['name' => 'libelle', 'label' => 'Libellé', 'type' => 'text'],
                ['name' => 'libelle_arb', 'label' => 'Libellé (Arabe)', 'type' => 'text'],
            ],
        ]);
    }

    public function update(Request $request, Site $site)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);
        $site->update($data);
        return redirect()->route('sites.index')->with('success', 'Site mis à jour.');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site supprimé.');
    }
}
