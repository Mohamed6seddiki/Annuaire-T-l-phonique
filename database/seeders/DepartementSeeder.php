<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['libelle' => 'IT', 'libelle_arb' => 'تقنية المعلومات'],
            ['libelle' => 'Audio', 'libelle_arb' => 'الصوت'],
            ['libelle' => 'Publicité', 'libelle_arb' => 'الإشهار'],
            ['libelle' => 'Comptabilité', 'libelle_arb' => 'المحاسبة'],
            ['libelle' => 'Personnel', 'libelle_arb' => 'شؤون الموظفين'],
        ];
        foreach ($items as $item) {
            Departement::create($item);
        }
    }
}
