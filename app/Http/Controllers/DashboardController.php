<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Direction;
use App\Models\Sdirection;
use App\Models\Departement;
use App\Models\Site;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search', '');
    $type = $request->get('type', '');

    $query = Standard::with(['direction', 'sdirection', 'departement', 'site']);

    $this->applySearch($query, $search, $type);

    $standards = $query->orderBy('nom')->paginate(5);

    if ($request->ajax()) {
        $allFilteredQuery = Standard::with(['direction', 'sdirection', 'departement', 'site']);
        $this->applySearch($allFilteredQuery, $search, $type);
        $allFiltered = $allFilteredQuery->orderBy('nom')->get()->map(fn($s) => $this->formatStandard($s));

        return response()->json([
            'employees' => $standards->map(fn($s) => $this->formatStandard($s)),
            'all_filtered' => $allFiltered,
            'pagination' => [
                'current_page' => $standards->currentPage(),
                'last_page' => $standards->lastPage(),
                'total' => $standards->total(),
                'count' => $standards->count(),
            ],
        ]);
    }

    
    $formattedStandards = $standards->through(fn($s) => $this->formatStandard($s));

    return view('dashboard', [
        'employees' => $formattedStandards,  
        'allEmployees' => Standard::with(['direction', 'sdirection', 'departement', 'site'])
            ->orderBy('nom')->get()->map(fn($s) => $this->formatStandard($s))->toArray(),
        'directions' => Direction::all(),
        'sdirections' => Sdirection::all(),
        'departements' => Departement::all(),
        'sites' => Site::all(),
    ]);
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

        return response()->json(['success' => true]);
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

        return response()->json(['success' => true]);
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return response()->json(['success' => true]);
    }

    private function applySearch($query, $search, $type)
    {
        if (!$search) return;

        $searchable = ['nom', 'numero', 'service'];
        if ($type && in_array($type, $searchable)) {
            $query->where($type, 'like', "%{$search}%");
        } elseif ($type === 'direction') {
            $query->whereHas('direction', fn($q) => $q->where('libelle', 'like', "%{$search}%"));
        } elseif ($type === 'sous_direction') {
            $query->whereHas('sdirection', fn($q) => $q->where('libelle', 'like', "%{$search}%"));
        } elseif ($type === 'departement') {
            $query->whereHas('departement', fn($q) => $q->where('libelle', 'like', "%{$search}%"));
        } elseif ($type === 'site') {
            $query->whereHas('site', fn($q) => $q->where('libelle', 'like', "%{$search}%"));
        } else {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhereHas('direction', fn($q) => $q->where('libelle', 'like', "%{$search}%"))
                  ->orWhereHas('sdirection', fn($q) => $q->where('libelle', 'like', "%{$search}%"))
                  ->orWhereHas('departement', fn($q) => $q->where('libelle', 'like', "%{$search}%"))
                  ->orWhereHas('site', fn($q) => $q->where('libelle', 'like', "%{$search}%"));
            });
        }
    }

    private function formatStandard($s): array
    {
        $palette = [
            ['color' => '#1d4ed8', 'bg' => '#eff6ff', 'border' => '#bfdbfe'],
            ['color' => '#15803d', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
            ['color' => '#7c3aed', 'bg' => '#f5f3ff', 'border' => '#ddd6fe'],
            ['color' => '#b45309', 'bg' => '#fffbeb', 'border' => '#fde68a'],
            ['color' => '#be123c', 'bg' => '#fff1f2', 'border' => '#fecdd3'],
            ['color' => '#0f766e', 'bg' => '#f0fdfa', 'border' => '#ccfbf1'],
        ];
        $idx = crc32($s->service ?? $s->nom) % count($palette);

        return [
            'id' => $s->id,
            'nom' => $s->nom,
            'numero' => $s->numero,
            'direction' => $s->direction?->libelle ?? '',
            'direction_id' => $s->id_direction,
            'sous_direction' => $s->sdirection?->libelle ?? '',
            'sous_direction_id' => $s->id_sdirection,
            'departement' => $s->departement?->libelle ?? '',
            'departement_id' => $s->id_departement,
            'service' => $s->service ?? '',
            'site' => $s->site?->libelle ?? '',
            'site_id' => $s->id_site,
            'niveau' => $s->niveau ?? '',
            'type' => $s->type ?? '',
            'service_color' => $palette[$idx]['color'],
            'service_bg' => $palette[$idx]['bg'],
            'service_border' => $palette[$idx]['border'],
        ];
    }
}
