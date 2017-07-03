<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogoutTest extends DuskTestCase
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
                    ->clickLink('HealthcarePro')
                    ->assertSee('Logout')
                    ->pause(1000)
                    ->clickLink('Logout')
                    ->assertPathIs('/')
                    ->assertSee('Login')
                    ->assertSee('Username')
                    ->assertSee('Password')
                    ->pause(1000);
        });
    }
}
