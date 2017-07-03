<?php

namespace Tests\Browser;

use App\Question;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionWithOptionsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        
        //for($i = 1; $i <= count(Question::all()); $i++) {
            $question = Question::find(4);
        /*    if($question->type == 'radio') {
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

            $browser->assertSeeIn('table ', $question->question)
                        ->assertSeeIn('a[href=\'http://192.168.99.100/questions/4\']', 'Detalhes')
                        ->click('a[href=\'http://192.168.99.100/questions/4\']', 'Detalhes');

            $browser->assertPathIs('/'.'questions/'.$question->id)
                    ->assertSeeIn('h2', 'Questão: '.$question->question)
                    ->assertSeeIn('h4:first-child', 'Tipo de Resposta: Opções')
                    ->assertSeeIn('h4:nth-child(2)', 'Criador: '.$question->creator->username)
                    ->assertSeeIn('h4:nth-child(3)', 'Data da criação: '.(string)$question->created_at)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da última atualização: '.(string)$question->updated_at);

            $answers = explode(";" ,$question->values);

            for($i = 0; $i <= (count($answers)-2); $i++) {
                $browser->assertSeeIn('.answers', $answers[$i]);
            }
        });
    }
}
