<?php

namespace Tests\Browser;

use App\Evaluation;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientQuizEvaluationDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    /**
     * @group healthcarepro
     */
    public function testExample()
    {

        $evaluation = Evaluation::find(7);

        $this->browse(function (Browser $browser) use ($evaluation) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->assertPathIs('/caregivers/15/patients')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->assertPathIs('/patients/10')
                    ->assertSeeIn('table', $evaluation->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/evaluations/7\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/evaluations/7\']', 'Detalhes')
                    ->assertPathIs('/evaluations/7')
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
