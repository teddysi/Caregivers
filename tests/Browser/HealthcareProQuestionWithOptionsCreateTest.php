<?php

namespace Tests\Browser;

use App\Question;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionWithOptionsCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $resources = new ResourcesDropDownTest();
        $resources->testBasicExample();

        $new_question = [
            'Question Test',
            'some;text;for;both;examples;'
        ];

        $this->browse(function (Browser $browser) use ($new_question){
            $browser->clickLink('Questões')
                    ->assertPathIs('/caregivers/public/questions')
                    ->clickLink('Nova Questão')
                    ->assertPathIs('/caregivers/public/questions/create')
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
                    ->assertPathIs('/caregivers/public/questions');

            $count_questions = count(Question::all());
            $question = Question::find($count_questions);

            if($question->question != $new_question[0]) {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('table tr:first-child td:first-child', $question->question)
                    ->assertSeeIn('table tr:first-child td:last-child .btn-primary', 'Detalhes')
                    ->click('table tr:first-child td:last-child .btn-primary', 'Detalhes')
                    ->assertSeeIn('h2', 'Questão: '.$question->question);

            $answers = explode(";" ,$question->values);

            for($i = 0; $i <= (count($answers)-2); $i++) {
                $browser->assertSeeIn('.answers', $answers[$i]);
            }

                    
            $browser->pause(3000);

        });
    }
}
