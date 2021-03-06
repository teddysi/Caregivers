<?php

namespace Tests\Browser;

use App\Caregiver;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProCaregiverBlockTest extends DuskTestCase
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
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(5) button.btn-danger', 'Bloquear')
                    ->click('table tr:first-child td:last-child div:nth-child(5) button', 'Bloquear')
                    ->assertPathIs('/')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(5) button.btn-success', 'Desbloquear')
                    ->pause(2000);

            $caregiver = Caregiver::find(15);

            if(!$caregiver->blocked) {
                $this->assertTrue(false);
            }

        });
    }
}
