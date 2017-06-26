<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProDashboardCaregiverDesassociateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $this->browse(function (Browser $browser) {
            $browser->assertSee('Caregiver')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(6) button.btn-danger', 'Desassociar')
                    ->click('table tr:first-child td:last-child div:nth-child(6) button.btn-danger', 'Desassociar')
                    ->assertPathIs('/caregivers/public/')
                    ->assertSee('Não existem cuidadores a meu cargo.')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'caregiver@mail.com')
                    ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(3) button', 'Bloquear')
                    ->assertSeeIn('table tr:first-child td:last-child button.btn-success', 'Associar')
                    ->pause(2000);

            $healthcarepro = HealthcarePro::find(14);

            foreach ($healthcarepro->caregivers as $caregiver) {
                if($caregiver->name == 'Caregiver') {
                    $this->assertTrue(false);
                }
            }

        });
    }
}
