<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Patient;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'test',
            'test@gmail.com',
            'test'
        ];

        $patient = Patient::find(10);

        $this->browse(function (Browser $browser) use ($patient, $fields_to_update)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Utentes')
                    ->assertPathIs('/patients')
                    ->assertSeeIn('table ', $patient->name)
                    ->assertSeeIn('table ', $patient->email)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'patients/'.$patient->id.'/edit')
                    ->assertInputValue('name', $patient->name)
                    ->assertInputValue('email', $patient->email)
                    ->assertInputValue('location', $patient->location)
                    ->type('name', $fields_to_update[0])
                    ->type('email', $fields_to_update[1])
                    ->type('location', $fields_to_update[2])
                    ->press('Guardar')
                    ->assertPathIs('/patients')
                    ->pause(2000);

            $patient = Patient::find(10);

            if($patient->name != $fields_to_update[0] || $patient->email != $fields_to_update[1] || $patient->location != $fields_to_update[2]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $patient->name)
                    ->assertSeeIn('table ', $patient->email)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->assertPathIs('/'.'patients/'.$patient->id)
                    ->assertSeeIn('h2', 'Utente: '.$patient->name)
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
