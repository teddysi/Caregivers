<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\SuccessfullyLoginTest;

class LogoutTest extends DuskTestCase
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
            $browser->clickLink('HealthcarePro')
                    ->assertSee('Logout')
                    ->pause(1000)
                    ->clickLink('Logout')
                    ->assertPathIs('/caregivers/public/')
                    ->assertSee('Login')
                    ->assertSee('Username')
                    ->assertSee('Password')
                    ->pause(1000);
        });
    }
}
