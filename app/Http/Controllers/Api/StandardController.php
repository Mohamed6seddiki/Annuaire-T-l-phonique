<?php

namespace App\Http\Controllers\Api;

use App\Models\Standard;
use App\Models\Direction;
use App\Models\Sdirection;
use App\Models\Departement;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::with(['direction', 'sdirection', 'departement', 'site'])
            ->orderBy('nom')
            ->paginate(10);

        return response()->json($standards);
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

        $standard = Standard::create($data);
        $standard->load(['direction', 'sdirection', 'departement', 'site']);

        return response()->json($standard, 201);
    }

    public function show(Standard $standard)
    {
        $standard->load(['direction', 'sdirection', 'departement', 'site']);

        return response()->json($standard);
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
        $standard->load(['direction', 'sdirection', 'departement', 'site']);

        return response()->json($standard);
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return response()->json(null, 204);
    }

    public function relations()
    {
        return response()->json([
            'directions' => Direction::all(),
            'sdirections' => Sdirection::all(),
            'departements' => Departement::all(),
            'sites' => Site::all(),
        ]);
    }
}
