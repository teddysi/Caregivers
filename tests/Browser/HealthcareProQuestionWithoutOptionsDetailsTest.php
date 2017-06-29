<?php

namespace Tests\Browser;

use App\Question;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionWithoutOptionsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        
        //for($i = 1; $i <= count(Question::all()); $i++) {
        $question = Question::find(1);
        /*    if($question->type == 'text') {
                break;
            }
        } */

        $this->browse(function (Browser $browser) use ($question){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Recursos')
                    ->assertSee('Questões')
                    ->clickLink('Questões')
                    ->assertPathIs('/questions');

            //if($i == 1) {
                $browser->assertSeeIn('table', $question->question)
                        ->assertSeeIn('a[href=\'http://192.168.99.100/questions/1\']', 'Detalhes')
                        ->click('a[href=\'http://192.168.99.100/questions/1\']', 'Detalhes');
           /* } else if ($i == count(Question::all())) {
                $browser->assertSeeIn('table tr:last-child td:first-child', $question->question)
                        ->assertSeeIn('table tr:last-child td:last-child div div:first-child a', 'Detalhes')
                        ->click('table tr:last-child td:last-child div div:first-child a', 'Detalhes');   
            } else {
                $browser->assertSeeIn('table tr:nth-child('.$i.') td:first-child', $question->question)
                        ->assertSeeIn('table tr:nth-child('.$i.') td:last-child div div:first-child a', 'Detalhes')
                        ->click('table tr:nth-child('.$i.') td:last-child div div:first-child a', 'Detalhes');
            }*/

            $browser->assertPathIs('/'.'questions/'.$question->id)
                    ->assertSeeIn('h2', 'Questão: '.$question->question)
                    ->assertSeeIn('h4:first-child', 'Tipo de Resposta: Texto')
                    ->assertSeeIn('h4:nth-child(2)', 'Criador: '.$question->creator->username)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da criação: '.(string)$question->created_at)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da última atualização: '.(string)$question->updated_at);
        });
    }
}
