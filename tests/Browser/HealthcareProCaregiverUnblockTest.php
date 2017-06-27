<?php

namespace Tests\Browser;

use App\Caregiver;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\SuccessfullyLoginTest;

class HealthcareProCaregiverUnblockTest extends DuskTestCase
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

        $this->browse(function (Browser $browser) {
            $browser->assertSee('Caregiver')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(5) button.btn-danger', 'Bloquear')
                    ->click('table tr:first-child td:last-child div:nth-child(5) button', 'Bloquear')
                    ->assertPathIs('/caregivers/public/')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(5) button.btn-success', 'Desbloquear')
                    ->click('table tr:first-child td:last-child div:nth-child(5) button', 'Desbloquear')
                    ->assertPathIs('/caregivers/public/')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(5) button.btn-danger', 'Bloquear')
                    ->pause(2000);

            $caregiver = Caregiver::find(15);

            if($caregiver->blocked) {
                $this->assertTrue(false);
            }
        });
    }
}
