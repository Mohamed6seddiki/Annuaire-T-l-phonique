<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Direction;
use App\Models\Sdirection;
use App\Models\Departement;
use App\Models\Site;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::with(['direction', 'sdirection', 'departement', 'site'])
            ->orderBy('nom')
            ->paginate(10);

        return view('standards.index', compact('standards'));
    }

    public function create()
    {
        $directions = Direction::all();
        $sdirections = Sdirection::all();
        $departements = Departement::all();
        $sites = Site::all();

        return view('standards.form', compact('directions', 'sdirections', 'departements', 'sites'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|string|max:255|unique:standards,numero',
            'nom' => 'required|string|max:255',
            'id_direction' => 'required|exists:directions,id',
            'id_sdirection' => 'required|exists:sdirections,id',
            'id_departement' => 'required|exists:departements,id',
            'service' => 'nullable|string|max:255',
            'id_site' => 'required|exists:sites,id',
            'niveau' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        Standard::create($data);

        return redirect()->route('referentiel.index')
            ->with('success', 'Employé créé avec succès.');
    }

    public function show(Standard $standard)
    {
        $standard->load(['direction', 'sdirection', 'departement', 'site']);

        return view('standards.show', compact('standard'));
    }

    public function edit(Standard $standard)
    {
        $directions = Direction::all();
        $sdirections = Sdirection::all();
        $departements = Departement::all();
        $sites = Site::all();

        return view('standards.form', compact('standard', 'directions', 'sdirections', 'departements', 'sites'));
    }

    public function update(Request $request, Standard $standard)
    {
        $data = $request->validate([
            'numero' => 'required|string|max:255|unique:standards,numero,' . $standard->id,
            'nom' => 'required|string|max:255',
            'id_direction' => 'required|exists:directions,id',
            'id_sdirection' => 'required|exists:sdirections,id',
            'id_departement' => 'required|exists:departements,id',
            'service' => 'nullable|string|max:255',
            'id_site' => 'required|exists:sites,id',
            'niveau' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $standard->update($data);

        return redirect()->route('referentiel.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return redirect()->route('referentiel.index')
            ->with('success', 'Employé supprimé avec succès.');
    }
}
