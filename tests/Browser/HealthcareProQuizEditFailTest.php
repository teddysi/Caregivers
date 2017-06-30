<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProQuizEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
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
                    ->assertSeeIn('a[href=\'http://192.168.99.100/quizs/2/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/quizs/2/edit\']', 'Editar')
                    ->assertPathIs('/quizs/2/edit')
                    ->type('name', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/quizs/2/edit')
                    ->assertSee($messages[0])
                    ->type('name', 'a')
                    ->press('Guardar')
                    ->assertPathIs('/quizs/2/edit')
                    ->assertSee($messages[1])
                    ->pause(2000);
        });
    }
}
