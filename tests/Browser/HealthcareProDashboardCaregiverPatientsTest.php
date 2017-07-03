<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Caregiver;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProDashboardCaregiverPatientsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(2) a', 'Utentes')
                    ->click('table tr:first-child td:last-child div:nth-child(2) a', 'Utentes')
                    ->assertPathIs('/caregivers/15/patients')
                    ->assertSee('Utentes de Caregiver')
                    ->assertSeeIn('table th:first-child', 'Nome')
                    ->assertSeeIn('table th:nth-child(2)', 'Email')
                    ->assertSeeIn('table th:nth-child(3)', 'Localização')
                    ->assertSeeIn('table th:last-child', 'Ações')
                    ->pause(2000);

            $caregiver = Caregiver::find(15);

            if(!count($caregiver->patients)) {
                $browser->assertSee('Não existem utentes associados a este Cuidador.');
            } else {
                $browser->assertDontSee('Não existem utentes associados a este Cuidador.');
                $i = 1;
                foreach ($caregiver->patients as $patient) {
                    if( $i == 1) {
                        $browser->assertSeeIn('table tr:first-child td:first-child', $patient->name)
                                ->assertSeeIn('table tr:first-child td:nth-child(2)', $patient->email)
                                ->assertSeeIn('table tr:first-child td:nth-child(3)', $patient->location)
                                ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                                ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Necessidades')
                                ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(3) a', 'Editar')
                                ->assertSeeIn('table tr:first-child td:last-child div div:last-child button', 'Desassociar');
                    } else if( $i == count($caregiver->patients) && count($caregiver->patients) > 1) {
                        $browser->assertSeeIn('table tr:last-child td:first-child', $patient->name)
                                ->assertSeeIn('table tr:last-child td:nth-child(2)', $patient->email)
                                ->assertSeeIn('table tr:last-child td:nth-child(3)', $patient->location)
                                ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                                ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Necessidades')
                                ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(3) a', 'Editar')
                                ->assertSeeIn('table tr:first-child td:last-child div div:last-child button', 'Desassociar');
                    } else {
                        $browser->assertSeeIn('table tr:nth-child('.$i.') td:first-child', $patient->name)
                                ->assertSeeIn('table tr:nth-child('.$i.') td:nth-child(2)', $patient->email)
                                ->assertSeeIn('table tr:nth-child('.$i.') td:nth-child(3)', $patient->location)
                                ->assertSeeIn('table tr:nth-child('.$i.') td:last-child div div:first-child a', 'Detalhes')
                                ->assertSeeIn('table tr:nth-child('.$i.') td:last-child div div:nth-child(2) a', 'Necessidades')
                                ->assertSeeIn('table tr:nth-child('.$i.') td:last-child div div:nth-child(3) a', 'Editar')
                                ->assertSeeIn('table tr:nth-child('.$i.') td:last-child div div:last-child button', 'Desassociar');
                    }
                    $i++;
                }
            }

        });
    }
}
