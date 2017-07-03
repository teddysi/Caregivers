<?php

namespace Tests\Browser;

use App\Patient;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientNeedsAssociateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testExample()
    {

        $patient = Patient::find(10);
        $count_patient_needs = count($patient->needs);

        $this->browse(function (Browser $browser) use ($patient, $count_patient_needs){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Utentes')
                    ->assertPathIs('/patients')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/needs\']', 'Necessidades')
                    ->click('a[href=\'http://192.168.99.100/patients/'.$patient->id.'/needs\']', 'Necessidades')
                    ->assertPathIs('/patients/10/needs')
                    ->assertSeeIn('table.other-needs tr:first-child td:last-child button.btn-success', 'Associar')
                    ->click('table.other-needs tr:first-child td:last-child button.btn-success', 'Associar')
                    ->assertPathIs('/patients/10/needs')
                    ->pause(2000);

            $patient = Patient::find(10);

            if(($count_patient_needs + 1) != count($patient->needs)) {
                $this->assertTrue(false);
            }

            foreach ($patient->needs as $need) {
                $browser->assertSeeIn('table.patient-needs', $need->description);
            }

        });
    }
}
