<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index()
    {
        $directions = Direction::withCount('standards')->orderBy('libelle')->get();
        return view('ref.index', [
            'title' => 'Directions',
            'items' => $directions,
            'fields' => ['libelle', 'libelle_arb'],
            'route' => 'directions',
        ]);
    }

    public function create()
    {
        return view('ref.form', [
            'title' => 'Nouvelle direction',
            'route' => 'directions',
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
        Direction::create($data);
        return redirect()->route('directions.index')->with('success', 'Direction créée.');
    }

    public function show(Direction $direction)
    {
        return redirect()->route('referentiel.index');
    }

    public function edit(Direction $direction)
    {
        return view('ref.form', [
            'title' => 'Modifier la direction',
            'route' => 'directions',
            'item' => $direction,
            'fields' => [
                ['name' => 'libelle', 'label' => 'Libellé', 'type' => 'text'],
                ['name' => 'libelle_arb', 'label' => 'Libellé (Arabe)', 'type' => 'text'],
            ],
        ]);
    }

    public function update(Request $request, Direction $direction)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);
        $direction->update($data);
        return redirect()->route('directions.index')->with('success', 'Direction mise à jour.');
    }

    public function destroy(Direction $direction)
    {
        $direction->delete();
        return redirect()->route('directions.index')->with('success', 'Direction supprimée.');
    }
}
