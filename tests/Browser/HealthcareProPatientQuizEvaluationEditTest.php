<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Evaluation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientQuizEvaluationEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'Eval test',
            'testestest',
            'aaaaaaaaaaaaa'
        ];

        $evaluation = Evaluation::find(7);

        $this->browse(function (Browser $browser) use ($evaluation, $fields_to_update)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->assertPathIs('/caregivers/15/patients')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->assertPathIs('/patients/10')
                    ->assertSeeIn('table ', $evaluation->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/evaluations/'.$evaluation->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/evaluations/'.$evaluation->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'evaluations/7/edit')
                    ->assertInputValue('description', $evaluation->description)
                    ->assertInputValue('type', $evaluation->type)
                    ->assertInputValue('model', $evaluation->model)
                    ->type('description', $fields_to_update[0])
                    ->type('type', $fields_to_update[1])
                    ->type('model', $fields_to_update[2])
                    ->press('Guardar')
                    ->assertPathIs('/patients/10')
                    ->pause(2000);

            $evaluation = Evaluation::find(7);

            if($evaluation->description != $fields_to_update[0] || $evaluation->type != $fields_to_update[1] || $evaluation->model != $fields_to_update[2]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $evaluation->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/evaluations/7\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/evaluations/7\']', 'Detalhes')
                    ->assertPathIs('/'.'evaluations/'.$evaluation->id)
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
