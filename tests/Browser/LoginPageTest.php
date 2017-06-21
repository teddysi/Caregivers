<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/caregivers/public/')
                    ->assertSee('Username')
                    ->assertInputValue('#username', '')
                    ->assertSee('Password')
                    ->assertInputValue('input#password', '')
                    ->assertVisible('button.btn-primary', 'Login');
        });
    }
}
