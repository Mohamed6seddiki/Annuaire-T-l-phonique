<?php

namespace Database\Seeders;

use App\Models\Direction;
use Illuminate\Database\Seeder;

class DirectionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['libelle' => 'Technique', 'libelle_arb' => 'التقنية'],
            ['libelle' => 'Commerciale', 'libelle_arb' => 'التجارية'],
            ['libelle' => 'Production', 'libelle_arb' => 'الإنتاج'],
            ['libelle' => 'Administration', 'libelle_arb' => 'الإدارة'],
            ['libelle' => 'Finances', 'libelle_arb' => 'المالية'],
        ];
        foreach ($items as $item) {
            Direction::create($item);
        }
    }
}
