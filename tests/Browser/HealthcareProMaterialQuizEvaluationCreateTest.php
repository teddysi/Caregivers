<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Evaluation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProMaterialQuizEvaluationCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        $new_evaluation = [
            'This is a test',
            'Normal test'
        ];

        $this->browse(function (Browser $browser) use ($new_evaluation){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/materials\']', 'Materiais')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/materials\']', 'Materiais')
                    ->assertPathIs('/caregivers/15/materials')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/materials/1/rate\']', 'Avaliações')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/materials/1/rate\']', 'Avaliações')
                    ->assertPathIs('/caregivers/15/materials/1/rate')
                    ->assertSee('Disponibilizar Questionário')
                    ->clickLink('Disponibilizar Questionário')
                    ->assertPathIs('/caregivers/15/materials/1/evaluations/create')
                    ->assertSee('Descrição')
                    ->assertSee('Tipo de Avaliação')
                    ->assertSee('Questionário')
                    ->assertSee('Submeter Avaliação')
                    ->type('description', $new_evaluation[0])
                    ->type('type', $new_evaluation[1])
                    ->click('select option:last-child', 'Quiz-3')
                    ->press('Submeter Avaliação')
                    ->assertPathIs('/caregivers/15/materials/1/rate')
                    ->assertSeeIn('table tr:first-child td:first-child', $new_evaluation[0])
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_evaluation[1])
                    ->assertSeeIn('table tr:first-child td:nth-child(3)', 'Questionário Quiz-3')
                    ->assertSeeIn('table tr:first-child td:nth-child(4)', 'healthcarePro')
                    ->pause(2000);

            $evaluations_count = count(Evaluation::all());
            $evaluation = Evaluation::find($evaluations_count);

            if($evaluation->description != $new_evaluation[0]) {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('table tr:first-child td:nth-child(5)', (string)$evaluation->created_at)
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/evaluations/9')
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
