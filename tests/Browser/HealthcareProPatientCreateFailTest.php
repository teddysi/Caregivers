<?php

namespace Tests\Browser;

use App\Patient;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $messages = [
            'Email já existente. Escolha outro.',
            'O email tem que ser válido.',
            'O email tem que ser preenchido.',
            'O nome tem que ser preenchido.',
            'O nome tem que ter pelo menos 4 letras.',
            'A localização tem que ser preenchida.',
            'A localização tem que ter pelo menos 4 letras.',
        ];

        $patient = Patient::find(10);

        $this->browse(function (Browser $browser) use ($patient, $messages) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Utentes')
                    ->assertPathIs('/patients')
                    ->clickLink('Novo Utente')
                    ->assertPathIs('/patients/create')
                    ->type('name', $patient->name)
                    ->type('email', $patient->email)
                    ->type('location', $patient->location)
                    ->press('Criar')
                    ->assertPathIs('/patients/create')
                    ->assertSee($messages[0])
                    ->type('name', 'a')
                    ->type('email', 'b@c')
                    ->type('location', 'c')
                    ->press('Criar')
                    ->assertPathIs('/patients/create')
                    ->assertSee($messages[1])
                    ->assertSee($messages[4])
                    ->assertSee($messages[6])
                    ->type('name', ' ')
                    ->type('email', ' ')
                    ->type('location', ' ')
                    ->press('Criar')
                    ->assertPathIs('/patients/create')
                    ->assertSee($messages[2])
                    ->assertSee($messages[3])
                    ->assertSee($messages[5])
                    ->pause(2000);
        });
    }
}
