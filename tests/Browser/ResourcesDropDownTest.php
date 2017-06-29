<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResourcesDropDownTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Recursos')
                    ->assertSee('Cuidadores')
                    ->assertSee('Utentes')
                    ->assertSee('Necessidades')
                    ->assertSee('Materiais')
                    ->assertSee('Questionários')
                    ->assertSee('Questões');
        });
    }
}
