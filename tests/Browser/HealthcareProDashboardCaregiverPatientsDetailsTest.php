<?php

namespace Tests\Browser;

use App\Caregiver;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProDashboardCaregiverPatientsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $login = new SuccessfullyLoginTest();
        $login->testBasicExample();

        $caregiver = Caregiver::find(15);
        $patient = $caregiver->patients->get(0);

        $this->browse(function (Browser $browser) use ($patient){
            $browser->visit('/caregivers/public/caregivers/15/patients')
                    ->assertSeeIn('table tr:first-child td:first-child', $patient->name)
                    ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->click('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertPathIs('/caregivers/public/patients/'.$patient->id)
                    ->assertSeeIn('h2', 'Paciente: '.$patient->name)
                    ->assertSeeIn('h4:first-child', 'Email: '.$patient->email)
                    ->assertSeeIn('h4:nth-child(2)', 'Localização: '.$patient->location)
                    ->assertSeeIn('h4:nth-child(3)', 'Cuidador: '.$patient->caregiver->username)
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$patient->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$patient->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$patient->updated_at)
                    ->pause(2000);
        });
    }
}
