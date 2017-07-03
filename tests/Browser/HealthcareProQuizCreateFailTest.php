<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuizCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testExample()
    {

        $messages = [
            'O nome tem que ser preenchido.',
            'O nome tem que ter pelo menos 4 letras.',
        ];

        $this->browse(function (Browser $browser) use ($messages) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Questionários')
                    ->assertPathIs('/quizs')
                    ->assertSee('Novo Questionário')
                    ->clickLink('Novo Questionário')
                    ->assertPathIs('/quizs/create')
                    ->type('name', ' ')
                    ->press('Adicionar Questões')
                    ->assertPathIs('/quizs/create')
                    ->assertSee($messages[0])
                    ->type('name', 'a')
                    ->press('Adicionar Questões')
                    ->assertPathIs('/quizs/create')
                    ->assertSee($messages[1])
                    ->pause(2000);
        });
    }
}
