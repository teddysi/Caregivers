<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\HealthcarePro;

class SuccessfullyLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        $user = HealthcarePro::find(14);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/caregivers/public/')
                    ->type('username', $user->username)
                    ->type('password', 'propw')
                    ->press('Login')
                    ->assertPathIs('/caregivers/public/')
                    ->assertSee('Os meus Cuidadores');
        });
    }
    
}