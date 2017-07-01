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

        $messages = [
            'Essa questão já existe.',
            'A questão tem que ser preenchida.',
            'A questão tem que ter um tamanho de pelo menos 8 letras.',
            'Tem que preencher o campo "Opções" com respostas.',
            'O campo "Opções" não pode começar com ";".',
            'O campo "Opções" tem que terminar com ";".',
            'O campo "Opções" não deve conter espaços em branco antes de ";".',
            'O campo "Opções" tem que ter pelo menos duas respostas.',
            'O campo "Opções" tem não pode ter opções entre ";" vazias.'
        ];

        $question_option_errors = [
            ';',
            'some;text;for;both;examples',
            'a;;',
            'a; ;'
        ];

        $this->browse(function (Browser $browser) use ($question_option_errors, $messages){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questões')
                    ->assertPathIs('/questions')
                    ->clickLink('Nova Questão')
                    ->assertPathIs('/questions/create')
                    ->assertSeeIn('select option:first-child','Texto')
                    ->type('question', 'Como está?')
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[0])
                    ->type('question', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[1])
                    ->type('question', 'aaa')
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[2])
                    ->click('select option:first-child','Texto')
                    ->click('select option:last-child','Opções')
                    ->type('values', $question_option_errors[0])
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[4])
                    ->assertSee($messages[7])
                    ->assertSee($messages[8])
                    ->type('values', $question_option_errors[1])
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[5])
                    ->type('values', $question_option_errors[2])
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[8])
                    ->type('values', $question_option_errors[3])
                    ->press('Guardar')
                    ->assertPathIs('/questions/create')
                    ->assertSee($messages[6])
                    ->pause(2000);


        });
    }
}
