<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */    
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Username')
                    ->assertInputValue('#username', '')
                    ->assertSee('Password')
                    ->assertInputValue('input#password', '')
                    ->assertVisible('button.btn-primary', 'Login');
        });
    }
}
