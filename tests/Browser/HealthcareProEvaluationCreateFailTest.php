<?php

namespace Tests\Browser;

use Storage;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProEvaluationCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
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

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        $this->browse(function (Browser $browser) use ($messages, $storagePath) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/caregivers/15/evaluations/create/eval')
                    ->assertPathIs('/caregivers/15/evaluations/create/eval')
                    ->type('description', ' ')
                    ->type('type', ' ')
                    ->type('model', ' ')
                    ->press('Submeter Avaliação')
                    ->assertPathIs('/caregivers/15/evaluations/create/eval')
                    ->assertSee($messages[0])
                    ->assertSee($messages[2])
                    ->assertSee($messages[4])
                    ->assertSee($messages[6])
                    ->type('description', 'a')
                    ->type('type', 'a')
                    ->type('model', 'a')
                    ->attach('path', $storagePath.'/images/Imagem-1.jpg')
                    ->press('Submeter Avaliação')
                    ->assertPathIs('/caregivers/15/evaluations/create/eval')
                    ->assertSee($messages[1])
                    ->assertSee($messages[3])
                    ->assertSee($messages[5])
                    ->pause(2000);

        });
    }
}
