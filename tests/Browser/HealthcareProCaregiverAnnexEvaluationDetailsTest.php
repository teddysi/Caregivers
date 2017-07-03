<?php

namespace Tests\Browser;

use App\Evaluation;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProCaregiverAnnexEvaluationDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    /**
     * @group healthcarepro
     */
    public function testExample()
    {

        $evaluation = Evaluation::find(4);

        $this->browse(function (Browser $browser) use ($evaluation) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Detalhes')
                    ->assertPathIs('/users/15')
                    ->clickLink('Avaliações')
                    ->assertPathIs('/caregivers/15/rate')
                    ->assertSeeIn('table', $evaluation->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/evaluations/4\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/evaluations/4\']', 'Detalhes')
                    ->assertPathIs('/evaluations/4')
                    ->assertSeeIn('h2', 'Avaliação: '.$evaluation->description)
                    ->assertSeeIn('h4:first-child', 'Tipo de Avaliação: '.$evaluation->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Modelo: '.$evaluation->model)
                    ->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                    ->assertVisible('h4:nth-child(3) a[href=\'http://192.168.99.100/evaluations/'.$evaluation->id.'/showContent\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$evaluation->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$evaluation->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$evaluation->updated_at)
                    ->pause(2000);
        });
    }
}
