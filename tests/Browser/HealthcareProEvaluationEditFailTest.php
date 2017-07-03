<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProEvaluationEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $messages = [
            'A descrição tem que ser preenchida.',
            'A descrição tem que ter pelo menos 4 letras.',
            'O tipo de avaliação tem que ser preenchido',
            'O tipo de avaliação tem que ter pelo menos 4 letras',
            'O modelo tem que ser preenchido',
            'O modelo tem que ter pelo menos 3 letras',
            'Introduza um ficheiro de avaliação.',
        ];

        $this->browse(function (Browser $browser) use ($messages) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/evaluations/4/edit')
                    ->assertPathIs('/evaluations/4/edit')
                    ->type('description', ' ')
                    ->type('type', ' ')
                    ->type('model', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/evaluations/4/edit')
                    ->assertSee($messages[0])
                    ->assertSee($messages[2])
                    ->assertSee($messages[4])
                    ->type('description', 'a')
                    ->type('type', 'a')
                    ->type('model', 'a')
                    ->press('Guardar')
                    ->assertPathIs('/evaluations/4/edit')
                    ->assertSee($messages[1])
                    ->assertSee($messages[3])
                    ->assertSee($messages[5])
                    ->pause(2000);

        });
    }
}
