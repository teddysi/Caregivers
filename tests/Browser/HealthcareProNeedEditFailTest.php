<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Need;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProNeedEditFailTest extends DuskTestCase
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
                    ->assertSeeIn('a[href=\'http://192.168.99.100/needs/2/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/needs/2/edit\']', 'Editar')
                    ->assertPathIs('/needs/2/edit')
                    ->type('description', $need->description)
                    ->press('Guardar')
                    ->assertPathIs('/needs/2/edit')
                    ->assertSee($messages[0])
                    ->type('description', 'a')
                    ->press('Guardar')
                    ->assertPathIs('/needs/2/edit')
                    ->assertSee($messages[2])
                    ->type('description', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/needs/2/edit')
                    ->assertSee($messages[1])
                    ->pause(2000);
        });
    }
}
