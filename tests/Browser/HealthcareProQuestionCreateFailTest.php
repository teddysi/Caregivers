<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Question;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $question_option_errors = [
            ';',
            'some;text;for;both;examples',
            ';;'

        ];

        $this->browse(function (Browser $browser) use ($new_question){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questões')
                    ->assertPathIs('/questions')
                    ->clickLink('Nova Questão')
                    ->assertPathIs('/questions/create')
                    ->assertSeeIn('select option:first-child','Texto')
                    ->type('question', $new_question[0])
                    ->click('select option:first-child','Texto')
                    ->pause(1500)
                    ->click('select option:last-child','Opções')
                    ->pause(5000)
                    ->assertSeeIn('#inputOptions label', 'Opções de Resposta')
                    ->assertSeeIn('#inputOptions h5', 'Nota: Cada opção deve ser separada e terminada por ";". Exemplo: "Gosto muito;Não gosto;Sim;Não;"')
                    ->type('values', $new_question[1])
                    ->press('Guardar')
                    ->assertPathIs('/questions');
        });
    }
}
