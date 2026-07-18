<?php

namespace Database\Seeders;

use App\Models\Standard;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['numero' => '001', 'nom' => 'Dupont', 'id_direction' => 1, 'id_sdirection' => 1, 'id_departement' => 1, 'service' => 'Support', 'id_site' => 1, 'niveau' => 'Cadre', 'type' => 'CDI'],
            ['numero' => '002', 'nom' => 'Leroy', 'id_direction' => 2, 'id_sdirection' => 3, 'id_departement' => 3, 'service' => 'Communication', 'id_site' => 2, 'niveau' => 'Agent', 'type' => 'CDI'],
            ['numero' => '003', 'nom' => 'Martin', 'id_direction' => 3, 'id_sdirection' => 5, 'id_departement' => 2, 'service' => 'Montage', 'id_site' => 1, 'niveau' => 'Technicien', 'type' => 'CDD'],
            ['numero' => '004', 'nom' => 'Bernard', 'id_direction' => 1, 'id_sdirection' => 2, 'id_departement' => 1, 'service' => 'Réseaux', 'id_site' => 3, 'niveau' => 'Cadre', 'type' => 'CDI'],
            ['numero' => '005', 'nom' => 'Dubois', 'id_direction' => 2, 'id_sdirection' => 4, 'id_departement' => 3, 'service' => 'Spots', 'id_site' => 4, 'niveau' => 'Agent', 'type' => 'CDD'],
        ];
        foreach ($items as $item) {
            Standard::create($item);
        }
    }
}
