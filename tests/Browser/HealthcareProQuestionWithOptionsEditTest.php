<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Question;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuestionWithOptionsEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'Is this a Test?',
            'Sim;Sim;Sim;'
        ];

        $question = Question::find(4);

        $this->browse(function (Browser $browser) use ($question, $fields_to_update)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questões')
                    ->assertPathIs('/questions')
                    ->assertSeeIn('table ', $question->question)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/questions/'.$question->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/questions/'.$question->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'questions/4/edit')
                    ->assertInputValue('question', $question->question)
                    ->assertInputValue('values', $question->values)
                    ->type('question', $fields_to_update[0])
                    ->type('values', $fields_to_update[1])
                    ->press('Guardar')
                    ->assertPathIs('/questions')
                    ->pause(2000);

            $question = Question::find(4);

            if($question->question != $fields_to_update[0] || $question->values != $fields_to_update[1]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $question->question)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/questions/4\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/questions/4\']', 'Detalhes')
                    ->assertPathIs('/'.'questions/'.$question->id)
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
