<?php

namespace Tests\Browser;

use App\HealthcarePro;
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

        $user_blocked = HealthcarePro::find(16);

        $this->browse(function ($browser) use ($user, $user_blocked) {
            $browser->visit('/')
                    ->type('username', $user[0])
                    ->type('password', $user[1])
                    ->press('Login')
                    ->assertPathIs('/')
                    ->assertSee('Estas credênciais não existem nos nossos registos.')
                    ->type('username', $user_blocked->username)
                    ->type('password', 'propw')
                    ->press('Login')
                    ->assertPathIs('/')
                    ->assertSee('A sua conta foi bloqueada.')
                    ->pause(5000);
        });
    }
}
