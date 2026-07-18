<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['libelle' => 'Alger', 'libelle_arb' => 'الجزائر'],
            ['libelle' => 'Oran', 'libelle_arb' => 'وهران'],
            ['libelle' => 'Constantine', 'libelle_arb' => 'قسنطينة'],
            ['libelle' => 'Annaba', 'libelle_arb' => 'عنابة'],
            ['libelle' => 'Tizi Ouzou', 'libelle_arb' => 'تيزي وزو'],
        ];
        foreach ($items as $item) {
            Site::create($item);
        }
    }
}
