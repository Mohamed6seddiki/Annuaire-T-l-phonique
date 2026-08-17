<?php

namespace Tests\Feature;

use App\Models\Departement;
use App\Models\Direction;
use App\Models\Sdirection;
use App\Models\Site;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_print_page_matches_current_dashboard_page(): void
    {
        $user = User::factory()->create();

        $direction = Direction::create(['libelle' => 'Direction Test', 'libelle_arb' => 'Direction Test']);
        $sdirection = Sdirection::create(['libelle' => 'Sous Direction Test', 'libelle_arb' => 'Sous Direction Test']);
        $departement = Departement::create(['libelle' => 'Département Test', 'libelle_arb' => 'Département Test']);
        $site = Site::create(['libelle' => 'Site Test', 'libelle_arb' => 'Site Test']);

        foreach (range(1, 12) as $index) {
            Standard::create([
                'numero' => sprintf('100%02d', $index),
                'nom' => 'Nom ' . $index,
                'id_direction' => $direction->id,
                'id_sdirection' => $sdirection->id,
                'id_departement' => $departement->id,
                'service' => 'Service ' . $index,
                'id_site' => $site->id,
                'niveau' => 'Niveau ' . $index,
                'type' => 'Type ' . $index,
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/print?print=1&page=2');

        $response->assertOk();
        $this->assertCount(5, $response->viewData('employees'));
    }
}
