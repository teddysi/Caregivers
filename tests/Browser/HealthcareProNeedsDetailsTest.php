<?php

namespace Tests\Browser;

use App\Need;
use App\Caregiver;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProNeedsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $caregiver = Caregiver::find(15);
        $patient = $caregiver->patients->get(0);
        $need = $patient->needs->get(0);

        $this->browse(function (Browser $browser) use ($patient, $need){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/caregivers/15/patients')
                    ->assertSeeIn('table ', $patient->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/needs\']', 'Necessidades')
                    ->click('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/needs\']', 'Necessidades')
                    ->assertPathIs('/'.'patients/'.$patient->id.'/needs')
                    ->assertSeeIn('table.patient-needs tr:first-child td:first-child', $need->description)
                    ->assertSeeIn('table.patient-needs tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->click('table.patient-needs tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertPathIs('/'.'needs/'.$need->id)
                    ->assertSeeIn('h2', 'Necessidade: '.$need->description)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$need->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$need->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$need->updated_at)
                    ->pause(2000);

            


        });
    }
}
