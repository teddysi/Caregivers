<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Evaluation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProMaterialQuizEvaluationDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;
   
   /**
     * @group healthcarepro
     */
    public function testExample()
    {

        $evaluation = Evaluation::find(8);

        $this->browse(function (Browser $browser) use ($evaluation) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/materials\']', 'Materiais')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/materials\']', 'Materiais')
                    ->assertPathIs('/caregivers/15/materials')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/materials/1/rate\']', 'Avaliações')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/materials/1/rate\']', 'Avaliações')
                    ->assertPathIs('/caregivers/15/materials/1/rate')
                    ->assertSeeIn('table', $evaluation->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/evaluations/8\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/evaluations/8\']', 'Detalhes')
                    ->assertPathIs('/evaluations/8')
                    ->assertSeeIn('h2', 'Avaliação: '.$evaluation->description)
                    ->assertSeeIn('h4:first-child', 'Tipo de Avaliação: '.$evaluation->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Modelo: '.$evaluation->model)
                    ->assertSeeIn('h4:nth-child(3)', 'Criador: '.$evaluation->creator->username)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da criação: '.(string)$evaluation->created_at)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da última atualização: '.(string)$evaluation->updated_at)
                    ->assertSeeIn('h4:last-child', 'Data da resposta: À espera de resposta')
                    ->pause(2000);
        });
    }
}
