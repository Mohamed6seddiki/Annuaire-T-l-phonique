<?php

namespace App\Http\Controllers;

use App\Models\Sdirection;
use Illuminate\Http\Request;

class SdirectionController extends Controller
{
    public function index()
    {
        $items = Sdirection::withCount('standards')->orderBy('libelle')->get();
        return view('ref.index', [
            'title' => 'Sous-directions',
            'items' => $items,
            'fields' => ['libelle', 'libelle_arb'],
            'route' => 'sdirections',
        ]);
    }

    public function create()
    {
        return view('ref.form', [
            'title' => 'Nouvelle sous-direction',
            'route' => 'sdirections',
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
        Sdirection::create($data);
        return redirect()->route('sdirections.index')->with('success', 'Sous-direction créée.');
    }

    public function show(Sdirection $sdirection)
    {
        return redirect()->route('referentiel.index');
    }

    public function edit(Sdirection $sdirection)
    {
        return view('ref.form', [
            'title' => 'Modifier la sous-direction',
            'route' => 'sdirections',
            'item' => $sdirection,
            'fields' => [
                ['name' => 'libelle', 'label' => 'Libellé', 'type' => 'text'],
                ['name' => 'libelle_arb', 'label' => 'Libellé (Arabe)', 'type' => 'text'],
            ],
        ]);
    }

    public function update(Request $request, Sdirection $sdirection)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);
        $sdirection->update($data);
        return redirect()->route('sdirections.index')->with('success', 'Sous-direction mise à jour.');
    }

    public function destroy(Sdirection $sdirection)
    {
        $sdirection->delete();
        return redirect()->route('sdirections.index')->with('success', 'Sous-direction supprimée.');
    }
}
