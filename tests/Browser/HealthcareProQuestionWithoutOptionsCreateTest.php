<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Question;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionWithoutOptionsCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $new_question = [
            'Question Test',
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
                    ->press('Guardar')
                    ->assertPathIs('/questions');

            $count_questions = count(Question::all());
            $question = Question::find($count_questions);

            if($question->question != $new_question[0]) {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('table tr:first-child td:first-child', $question->question)
                    ->assertSeeIn('table tr:first-child td:last-child .btn-primary', 'Detalhes')
                    ->click('table tr:first-child td:last-child .btn-primary', 'Detalhes')
                    ->assertSeeIn('h2', 'Questão: '.$question->question)
                    ->assertSeeIn('h4:first-child', 'Tipo de Resposta: Texto')
                    ->assertSeeIn('h4:nth-child(2)', 'Criador: '.$question->creator->username)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da criação: '.(string)$question->created_at)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da última atualização: '.(string)$question->updated_at);
        });
    }
}
