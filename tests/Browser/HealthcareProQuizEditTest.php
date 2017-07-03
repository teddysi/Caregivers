<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Quiz;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuizEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'test',
            'test@gmail.com',
            'test'
        ];

        $quiz = Quiz::find(2);
        $count_questions = count($quiz->questions());

        $this->browse(function (Browser $browser) use ($quiz, $fields_to_update, $count_questions)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questionários')
                    ->assertPathIs('/quizs')
                    ->assertSeeIn('table ', $quiz->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'/questions\']', 'Questões')
                    ->click('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'/questions\']', 'Questões')
                    ->assertPathIs('/quizs/2/questions')
                    ->assertSeeIn('.questions-to-associate tr:first-child td:last-child button', 'Adicionar')
                    ->click('.questions-to-associate tr:first-child td:last-child button', 'Adicionar')
                    ->assertPathIs('/quizs/2/questions')
                    ->clickLink('Concluído')
                    ->assertPathIs('/quizs');

            $browser->assertSeeIn('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'quizs/'.$quiz->id.'/edit')
                    ->assertInputValue('name', $quiz->name)
                    ->type('name', $fields_to_update[0])
                    ->press('Guardar')
                    ->assertPathIs('/quizs')
                    ->pause(2000);

            $quiz = Quiz::find(2);

            if($quiz->name != $fields_to_update[0]) {
                $this->assertTrue(false);
            }

            if(count($quiz->questions) == $count_questions) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/quizs/'.$quiz->id.'\']', 'Detalhes')
                    ->assertPathIs('/quizs/2')
                    ->assertSeeIn('h2', 'Questionário: '.$quiz->name)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$quiz->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$quiz->created_at)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da última atualização: '.(string)$quiz->updated_at)
                    ->pause(2000);
            foreach ($quiz->questions as $question) {
                $browser->assertSeeIn('table', $question->question);
            }
        });
    }
}
