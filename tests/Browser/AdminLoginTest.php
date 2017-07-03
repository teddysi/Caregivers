<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {
        $user = Admin::find(13);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/')
                    ->type('username', $user->username)
                    ->type('password', 'adminpw')
                    ->press('Login')
                    ->assertPathIs('/')
                    ->pause(5000);
        });
    }
}
