<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Quiz;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuizDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $quiz = Quiz::find(1);
        $question1 = $quiz->questions->get(0);
        $question2 = $quiz->questions->get(1);

        $this->browse(function (Browser $browser) use ($quiz, $question1, $question2){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Recursos')
                    ->assertSee('Questionários')
                    ->clickLink('Questionários')
                    ->assertPathIs('/quizs')
                    ->assertSeeIn('table', $quiz->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/quizs/1\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/quizs/1\']', 'Detalhes')
                    ->assertPathIs('/'.'quizs/'.$quiz->id)
                    ->assertSeeIn('h2', 'Questionário: '.$quiz->name)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$quiz->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$quiz->created_at)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da última atualização: '.(string)$quiz->updated_at)
                    ->assertSeeIn('table tr:first-child td:first-child', '1')
                    ->assertSeeIn('table tr:first-child td:last-child', $question1->question)
                    ->assertSeeIn('table tr:last-child td:first-child', '2')
                    ->assertSeeIn('table tr:last-child td:last-child', $question2->question)
                    ->pause(2000);
        });
    }
}
