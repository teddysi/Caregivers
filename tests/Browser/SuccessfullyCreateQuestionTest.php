<?php

namespace Tests\Browser;

use App\Question;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuccessfullyCreateQuestionTest extends DuskTestCase
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
            $browser->click('a.nav_questions')
                    ->assertPathIs('/caregivers/public/questions')
                    ->click('a.create_question_button')
                    ->assertPathIs('/caregivers/public/questions/create')
                    ->assertSeeIn('select.answer_type','Texto')
                    ->type('question', $new_question[0])
                    ->click('option.text')
                    ->pause(5000)
                    ->click('option.radio')
                    ->pause(5000)
                    ->type('values', $new_question[1])
                    ->press('Guardar')
                    ->assertPathIs('/caregivers/public/questions');

            $count_questions = count(Question::all());
            $question = Question::find($count_questions);

            if($question->question !== $new_question[0]) {
                $browser->assertSee('Error Creating Question! DB not updated');
            }

        });
    }
}
