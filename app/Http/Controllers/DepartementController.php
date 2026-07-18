<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $items = Departement::withCount('standards')->orderBy('libelle')->get();
        return view('ref.index', [
            'title' => 'Départements',
            'items' => $items,
            'fields' => ['libelle', 'libelle_arb'],
            'route' => 'departements',
        ]);
    }

    public function create()
    {
        return view('ref.form', [
            'title' => 'Nouveau département',
            'route' => 'departements',
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
        Departement::create($data);
        return redirect()->route('departements.index')->with('success', 'Département créé.');
    }

    public function show(Departement $departement)
    {
        return redirect()->route('referentiel.index');
    }

    public function edit(Departement $departement)
    {
        return view('ref.form', [
            'title' => 'Modifier le département',
            'route' => 'departements',
            'item' => $departement,
            'fields' => [
                ['name' => 'libelle', 'label' => 'Libellé', 'type' => 'text'],
                ['name' => 'libelle_arb', 'label' => 'Libellé (Arabe)', 'type' => 'text'],
            ],
        ]);
    }

    public function update(Request $request, Departement $departement)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);
        $departement->update($data);
        return redirect()->route('departements.index')->with('success', 'Département mis à jour.');
    }

    public function destroy(Departement $departement)
    {
        $departement->delete();
        return redirect()->route('departements.index')->with('success', 'Département supprimé.');
    }
}
