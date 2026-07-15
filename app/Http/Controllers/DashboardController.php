<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $allEmployees = $this->allEmployees();

        $search = $request->get('search', '');
        $type = $request->get('type', '');

        $filtered = collect($allEmployees);

        if ($search) {
            $searchable = ['nom', 'numero', 'direction', 'sous_direction', 'departement', 'service', 'site'];
            if ($type && in_array($type, $searchable)) {
                $filtered = $filtered->filter(fn($emp) => stripos($emp[$type] ?? '', $search) !== false);
            } else {
                $filtered = $filtered->filter(function ($emp) use ($search, $searchable) {
                    foreach ($searchable as $field) {
                        if (stripos($emp[$field] ?? '', $search) !== false) return true;
                    }
                    return false;
                });
            }
        }

        $perPage = 5;
        $page = (int) $request->get('page', 1);
        $total = $filtered->count();
        $items = $filtered->forPage($page, $perPage)->values()->all();

        $employees = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'employees' => $employees->items(),
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'total' => $employees->total(),
                    'count' => $employees->count(),
                ],
            ]);
        }

        return view('dashboard', [
            'employees' => $employees,
            'allEmployees' => $allEmployees,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'numero' => 'required|string|max:50',
            'direction' => 'required|string|max:255',
            'sous_direction' => 'required|string|max:255',
            'departement' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'site' => 'required|string|max:255',
        ]);

        $palette = $this->servicePalette();
        $employees = session()->get('employees', []);
        $index = count($employees) % count($palette);

        $data['id'] = 'emp_' . uniqid();
        $data['service_color'] = $palette[$index]['color'];
        $data['service_bg'] = $palette[$index]['bg'];
        $data['service_border'] = $palette[$index]['border'];

        $employees[] = $data;
        session()->put('employees', $employees);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'numero' => 'required|string|max:50',
            'direction' => 'required|string|max:255',
            'sous_direction' => 'required|string|max:255',
            'departement' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'site' => 'required|string|max:255',
        ]);

        $updates = session()->get('employee_updates', []);
        $updates[$id] = $data;
        session()->put('employee_updates', $updates);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $deleted = session()->get('employee_deletions', []);
        $deleted[$id] = true;
        session()->put('employee_deletions', $deleted);

        return response()->json(['success' => true]);
    }

    private function allEmployees(): array
    {
        $mock = $this->mockEmployees();
        $session = session()->get('employees', []);
        $updates = session()->get('employee_updates', []);
        $deletions = session()->get('employee_deletions', []);

        $session = array_map(function ($emp, $i) {
            if (!isset($emp['id'])) {
                $emp['id'] = 'legacy_' . $i;
            }
            return $emp;
        }, $session, array_keys($session));

        $all = array_merge($session, $mock);

        $all = array_filter($all, fn($emp) => !isset($deletions[$emp['id'] ?? null]));

        $all = array_map(function ($emp) use ($updates) {
            $id = $emp['id'] ?? null;
            if ($id && isset($updates[$id])) {
                return array_merge($emp, $updates[$id]);
            }
            return $emp;
        }, $all);

        return array_values($all);
    }

    private function mockEmployees(): array
    {
        return [
            [
                'id' => 'm0', 'nom' => 'Dupont', 'numero' => '0001',
                'direction' => 'Technique', 'sous_direction' => 'Réseaux',
                'departement' => 'IT', 'service' => 'Support', 'site' => 'Alger',
                'service_color' => '#1d4ed8', 'service_bg' => '#eff6ff', 'service_border' => '#bfdbfe',
            ],
            [
                'id' => 'm1', 'nom' => 'Leroy', 'numero' => '0002',
                'direction' => 'Commerciale', 'sous_direction' => 'Ventes',
                'departement' => 'Marketing', 'service' => 'Communication', 'site' => 'Oran',
                'service_color' => '#15803d', 'service_bg' => '#f0fdf4', 'service_border' => '#bbf7d0',
            ],
            [
                'id' => 'm2', 'nom' => 'Martin', 'numero' => '0003',
                'direction' => 'Production', 'sous_direction' => 'Studio',
                'departement' => 'Audio', 'service' => 'Montage', 'site' => 'Alger',
                'service_color' => '#7c3aed', 'service_bg' => '#f5f3ff', 'service_border' => '#ddd6fe',
            ],
            [
                'id' => 'm3', 'nom' => 'Bernard', 'numero' => '0004',
                'direction' => 'Technique', 'sous_direction' => 'Maintenance',
                'departement' => 'IT', 'service' => 'Réseaux', 'site' => 'Constantine',
                'service_color' => '#1d4ed8', 'service_bg' => '#eff6ff', 'service_border' => '#bfdbfe',
            ],
            [
                'id' => 'm4', 'nom' => 'Dubois', 'numero' => '0005',
                'direction' => 'Commerciale', 'sous_direction' => 'Marketing',
                'departement' => 'Publicité', 'service' => 'Spots', 'site' => 'Annaba',
                'service_color' => '#15803d', 'service_bg' => '#f0fdf4', 'service_border' => '#bbf7d0',
            ],
        ];
    }

    private function servicePalette(): array
    {
        return [
            ['color' => '#1d4ed8', 'bg' => '#eff6ff', 'border' => '#bfdbfe'],
            ['color' => '#15803d', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
            ['color' => '#7c3aed', 'bg' => '#f5f3ff', 'border' => '#ddd6fe'],
            ['color' => '#b45309', 'bg' => '#fffbeb', 'border' => '#fde68a'],
            ['color' => '#be123c', 'bg' => '#fff1f2', 'border' => '#fecdd3'],
            ['color' => '#0f766e', 'bg' => '#f0fdfa', 'border' => '#ccfbf1'],
        ];
    }
}
