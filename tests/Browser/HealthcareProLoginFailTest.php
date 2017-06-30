<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProLoginFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        $user = [
            'aaaaaaaaaaa',
            'bbbbbbbbbbbb'
        ];

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/')
                    ->type('username', $user[0])
                    ->type('password', $user[1])
                    ->press('Login')
                    ->assertPathIs('/')
                    ->assertSee('Estas credênciais não existem nos nossos registos.')
                    ->pause(5000);
        });
    }
}
