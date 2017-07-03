<?php

namespace Tests\Browser;

use App\Caregiver;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        $caregiver = Caregiver::find(15);
        $patient = $caregiver->patients->get(0);

        $this->browse(function (Browser $browser) use ($patient){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/caregivers/15/patients')
                    ->assertSeeIn('table', $patient->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/'.$patient->id.'\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/patients/'.$patient->id.'\']', 'Detalhes')
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
