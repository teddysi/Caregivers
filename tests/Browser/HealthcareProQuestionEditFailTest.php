<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $messages = [
            'Essa questão já existe',
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
                    ->visit('/questions/5/edit')
                    ->assertPathIs('/questions/5/edit')
                    ->type('question', 'Como está?')
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[0])
                    ->type('question', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[1])
                    ->type('question', 'aaa')
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[2])
                    ->type('values', $question_option_errors[0])
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[4])
                    ->assertSee($messages[7])
                    ->assertSee($messages[8])
                    ->type('values', $question_option_errors[1])
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[5])
                    ->type('values', $question_option_errors[2])
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[8])
                    ->type('values', $question_option_errors[3])
                    ->press('Guardar')
                    ->assertPathIs('/questions/5/edit')
                    ->assertSee($messages[6])
                    ->pause(2000);


        });
    }
}
