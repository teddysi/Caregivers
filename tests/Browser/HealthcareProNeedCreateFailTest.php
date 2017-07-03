<?php

namespace Tests\Browser;

use App\Need;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProNeedCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

    $messages = [
        'Já existe uma necessidade com essa descrição. Escolha outra.',
        'A descrição tem que ser preenchida.',
        'A descrição tem que ter pelo menos 5 letras.',
    ];

        $need = Need::find(3);

        $this->browse(function (Browser $browser) use ($need, $messages) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Necessidades')
                    ->assertPathIs('/needs')
                    ->clickLink('Nova Necessidade')
                    ->assertPathIs('/needs/create')
                    ->type('description', $need->description)
                    ->press('Criar')
                    ->assertPathIs('/needs/create')
                    ->assertSee($messages[0])
                    ->type('description', 'a')
                    ->press('Criar')
                    ->assertPathIs('/needs/create')
                    ->assertSee($messages[2])
                    ->type('description', ' ')
                    ->press('Criar')
                    ->assertPathIs('/needs/create')
                    ->assertSee($messages[1])
                    ->pause(2000);
        });
    }
}
