<?php

namespace Tests\Browser;

use App\Patient;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $patient_count = count(Patient::all());

        $this->browse(function (Browser $browser) use ($patient_count) {
            $browser->clickLink('Pacientes')
                    ->assertPathIs('/caregivers/public/patients')
                    ->clickLink('Novo Paciente')
                    ->assertPathIs('/caregivers/public/patients/create')
                    ->type('name', 'test')
                    ->type('email', 'test@gmail.com')
                    ->type('location', 'test')
                    ->press('Criar')
                    ->assertPathIs('/caregivers/public/patients');

            $patient_count_new = count(Patient::all());
            $new_patient = Patient::find($patient_count_new);
            
            if($patient_count + 1 !== $patient_count_new) {
                $this->assertFalse(true);
            }
            

            $browser->assertSeeIn('table tr:first-child td:first-child', $new_patient->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_patient->email)
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Necessidades')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/caregivers/public/patients/'.$new_patient->id)
                    ->assertSeeIn('h2', 'Paciente: '.$new_patient->name)
                    ->assertSeeIn('h4:first-child', 'Email: '.$new_patient->email)
                    ->assertSeeIn('h4:nth-child(2)', 'Localização: '.$new_patient->location)
                    ->assertSeeIn('h4:nth-child(3)', 'Cuidador: Não tem Cuidador')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$new_patient->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$new_patient->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$new_patient->updated_at)
                    ->pause(5000);
        });
    }
}
