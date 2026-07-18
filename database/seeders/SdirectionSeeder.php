<?php

namespace Database\Seeders;

use App\Models\Sdirection;
use Illuminate\Database\Seeder;

class SdirectionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['libelle' => 'Réseaux', 'libelle_arb' => 'الشبكات'],
            ['libelle' => 'Maintenance', 'libelle_arb' => 'الصيانة'],
            ['libelle' => 'Ventes', 'libelle_arb' => 'المبيعات'],
            ['libelle' => 'Marketing', 'libelle_arb' => 'التسويق'],
            ['libelle' => 'Studio', 'libelle_arb' => 'الاستوديو'],
            ['libelle' => 'Ressources Humaines', 'libelle_arb' => 'الموارد البشرية'],
        ];
        foreach ($items as $item) {
            Sdirection::create($item);
        }
    }
}
