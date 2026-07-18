<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Sdirection;
use App\Models\Departement;
use App\Models\Site;
use App\Models\Standard;
use Illuminate\Http\Request;

class ReferentielController extends Controller
{
    protected $models = [
        'direction' => Direction::class,
        'sdirection' => Sdirection::class,
        'departement' => Departement::class,
        'site' => Site::class,
    ];

    protected $labels = [
        'direction' => 'Direction',
        'sdirection' => 'Sous-direction',
        'departement' => 'Département',
        'site' => 'Site',
    ];

    public function index()
    {
        return view('referentiel.index', [
            'directions' => Direction::orderBy('libelle')->get(),
            'sdirections' => Sdirection::orderBy('libelle')->get(),
            'departements' => Departement::orderBy('libelle')->get(),
            'sites' => Site::orderBy('libelle')->get(),
            'standards' => Standard::with(['direction', 'sdirection', 'departement', 'site'])
                ->orderBy('nom')
                ->paginate(15),
        ]);
    }

    public function store(Request $request, $type)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);

        $modelClass = $this->models[$type] ?? null;
        abort_unless($modelClass, 404);

        $modelClass::create($data);

        return redirect()->route('referentiel.index')
            ->with('success', $this->labels[$type] . ' créé.');
    }

    public function update(Request $request, $type, $id)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'libelle_arb' => 'nullable|string|max:255',
        ]);

        $modelClass = $this->models[$type] ?? null;
        abort_unless($modelClass, 404);

        $modelClass::findOrFail($id)->update($data);

        return redirect()->route('referentiel.index')
            ->with('success', $this->labels[$type] . ' mis à jour.');
    }

    public function destroy($type, $id)
    {
        $modelClass = $this->models[$type] ?? null;
        abort_unless($modelClass, 404);

        $modelClass::findOrFail($id)->delete();

        return redirect()->route('referentiel.index')
            ->with('success', $this->labels[$type] . ' supprimé.');
    }
}
