<?php

namespace Tests\Browser;

use Tests\Browser\SuccessfullyLoginTest;
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
        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $this->browse(function (Browser $browser) {
            $browser->click('a.drop_recursos')
                    ->assertSee('Cuidadores')
                    ->assertSee('Pacientes')
                    ->assertSee('Necessidades')
                    ->assertSee('Materiais')
                    ->assertSee('Questionários')
                    ->assertSee('Questões');
        });
    }
}
