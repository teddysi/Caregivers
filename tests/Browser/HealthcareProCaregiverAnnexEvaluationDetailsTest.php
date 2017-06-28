<?php

namespace Tests\Browser;

use App\Evaluation;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProCaregiverAnnexEvaluationDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {

        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $evaluation = Evaluation::find(1);

        $this->browse(function (Browser $browser) use ($evaluation) {
            $browser->clickLink('Detalhes')
                    ->assertPathIs('/caregivers/public/users/15')
                    ->clickLink('Avaliações')
                    ->assertPathIs('/caregivers/public/caregivers/15/rate')
                    ->assertSeeIn('table tr:first-child td:first-child', $evaluation->description)
                    ->assertSeeIn('table tr:first-child td:last-child a.btn-primary', 'Detalhes')
                    ->click('table tr:first-child td:last-child a.btn-primary', 'Detalhes')
                    ->assertPathIs('/caregivers/public/evaluations/'.$evaluation->id)
                    ->assertSeeIn('h2', 'Avaliação: '.$evaluation->description)
                    ->assertSeeIn('h4:first-child', 'Tipo de Avaliação: '.$evaluation->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Modelo: '.$evaluation->model)
                    ->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                    ->assertVisible('h4:nth-child(3) a[href=\'http://192.168.99.100/caregivers/public/evaluations/'.$evaluation->id.'/showContent\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$evaluation->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$evaluation->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$evaluation->updated_at)
                    ->pause(2000);
        });
    }
}
