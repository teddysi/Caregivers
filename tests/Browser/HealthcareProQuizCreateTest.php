<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Quiz;
use DB;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuizCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $quizs_count = count(Quiz::all());

        $new_quiz = [
            'Test-quiz', //name
        ];
        

        $this->browse(function (Browser $browser) use ($new_quiz, $quizs_count){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questionários')
                    ->assertPathIs('/quizs')
                    ->assertSee('Novo Questionário')
                    ->clickLink('Novo Questionário')
                    ->assertPathIs('/quizs/create')
                    ->type('name', $new_quiz[0])
                    ->press('Adicionar Questões')
                    ->pause(2000);

            $quizs_count += 1;

            $browser->assertPathIs('/'.'quizs/'.$quizs_count.'/questions');

            $quiz = Quiz::find($quizs_count);

            if($quiz->name !== $new_quiz[0]) {
                $this->assertTrue(false);
            }

            for($i = 1; $i < 4; $i++) {
                $browser->assertSeeIn('.questions-to-associate tr:first-child td:last-child button', 'Adicionar')
                        ->click('.questions-to-associate tr:first-child td:last-child button', 'Adicionar')
                        ->assertPathIs('/'.'quizs/'.$quizs_count.'/questions');
                if($i === 1) {
                    $browser->assertSeeIn('.questions-associated tr:first-child td:last-child button', 'Remover');
                } else {
                    $browser->assertSeeIn('.questions-associated tr:nth-child('.$i.') td:last-child div.col-lg-4:last-child button', 'Remover');
                }
            }

            if(count($quiz->questions) !== 3) {
                    $this->assertTrue(false);
                }

            $browser->assertSeeIn('.questions-associated tr:first-child td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:last-child div.col-lg-4:first-child button', 'Cima')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertSeeIn('.questions-associated tr:last-child td:last-child div.col-lg-4:first-child button', 'Cima');

            $question1 = $quiz->questions->get(0);
            $question2 = $quiz->questions->get(1);
            $question3 = $quiz->questions->get(2);

            $browser->assertSeeIn('.questions-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.questions-associated tr:first-child td:nth-child(2)', $question1->question)
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:nth-child(2)', $question2->question)
                    ->assertSeeIn('.questions-associated tr:last-child td:first-child', '3')
                    ->assertSeeIn('.questions-associated tr:last-child td:nth-child(2)', $question3->question)
                    ->click('.questions-associated tr:first-child td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertPathIs('/'.'quizs/'.$quizs_count.'/questions')
                    ->assertSeeIn('.questions-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.questions-associated tr:first-child td:nth-child(2)', $question2->question)
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:nth-child(2)', $question1->question)
                    ->click('.questions-associated tr:last-child td:last-child div.col-lg-4:first-child button', 'Cima')
                    ->assertPathIs('/'.'quizs/'.$quizs_count.'/questions')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:nth-child(2)', $question3->question)
                    ->assertSeeIn('.questions-associated tr:last-child td:first-child', '3')
                    ->assertSeeIn('.questions-associated tr:last-child td:nth-child(2)', $question1->question);

            $orderQuestion1 = $orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question1->id]])->first()->order;

            $orderQuestion2 = $orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question2->id]])->first()->order;

            $orderQuestion3 = $orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question3->id]])->first()->order;

            if($orderQuestion1 != 3 || $orderQuestion2 != 1 || $orderQuestion3 != 2) {
                $this->assertTrue(false);
            }

            $browser->click('.questions-associated tr:nth-child(2) td:last-child div.col-lg-4:last-child button', 'Remover')
                    ->assertPathIs('/'.'quizs/'.$quizs_count.'/questions')
                    ->assertSeeIn('.questions-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.questions-associated tr:first-child td:nth-child(2)', $question2->question)
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.questions-associated tr:nth-child(2) td:nth-child(2)', $question1->question)
                    ->assertSeeIn('.questions-to-associate tr:first-child td:first-child', $question3->question)
                    ->assertSeeIn('.questions-to-associate tr:first-child td:last-child button', 'Adicionar');

            $quiz = Quiz::find($quizs_count);

            if(count($quiz->questions) !== 2) {
                    $this->assertTrue(false);
                }

            $question1 = $quiz->questions->get(0);
            $question2 = $quiz->questions->get(1);

            $orderQuestion1 = $orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question1->id]])->first()->order;

            $orderQuestion2 = $orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question2->id]])->first()->order;

            if($orderQuestion1 != 2 || $orderQuestion2 != 1) {
                $this->assertTrue(false);
            }

            $browser->pause(2000)
                    ->clickLink('Concluído')
                    ->assertPathIs('/quizs')
                    ->assertSeeIn('table', $quiz->name)
                    ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->click('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertPathIs('/'.'quizs/'.$quiz->id)
                    ->assertSeeIn('h2', 'Questionário: '.$quiz->name)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$quiz->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$quiz->created_at)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da última atualização: '.(string)$quiz->updated_at)
                    ->assertSeeIn('table tr:first-child td:first-child', '1')
                    ->assertSeeIn('table tr:first-child td:last-child', $question2->question)
                    ->assertSeeIn('table tr:last-child td:first-child', '2')
                    ->assertSeeIn('table tr:last-child td:last-child', $question1->question)
                    ->pause(2000);
        });
    }
}
