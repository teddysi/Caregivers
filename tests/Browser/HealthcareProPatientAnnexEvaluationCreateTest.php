<?php

namespace Tests\Browser;

use App\Evaluation;
use Storage;
use App\User;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProPatientAnnexEvaluationCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
        $new_evaluation = [
            'This is a test',
            'Normal test',
            'Model A'
        ];

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        $this->browse(function (Browser $browser) use ($new_evaluation, $storagePath){
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/patients\']', 'Utentes')
                    ->assertPathIs('/caregivers/15/patients')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/patients/10\']', 'Detalhes')
                    ->assertPathIs('/patients/10')
                    ->assertSee('Nova Avaliação')
                    ->clickLink('Nova Avaliação')
                    ->assertPathIs('/patients/10/evaluations/create/eval')
                    ->assertSee('Descrição')
                    ->assertSee('Tipo de Avaliação')
                    ->assertSee('Modelo')
                    ->assertSee('Ficheiro')
                    ->assertSee('Submeter Avaliação')
                    ->assertVisible('div.form-group:nth-child(6) input')
                    ->type('description', $new_evaluation[0])
                    ->type('type', $new_evaluation[1])
                    ->type('model', $new_evaluation[2])
                    ->attach('path', $storagePath.'/images/Imagem-1.jpg')
                    ->press('Submeter Avaliação')
                    ->assertPathIs('/patients/10')
                    ->assertSeeIn('table tr:first-child td:first-child', $new_evaluation[0])
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_evaluation[1])
                    ->assertSeeIn('table tr:first-child td:nth-child(3)', $new_evaluation[2])
                    ->assertSeeIn('table tr:first-child td:nth-child(4)', 'healthcarePro')
                    ->pause(2000);

            $evaluations_count = count(Evaluation::all());
            $evaluation = Evaluation::find($evaluations_count);

            if($evaluation->description != $new_evaluation[0]) {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('table tr:first-child td:nth-child(5)', (string)$evaluation->created_at)
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/'.'evaluations/'.$evaluations_count)
                    ->assertSeeIn('h2', 'Avaliação: '.$evaluation->description)
                    ->assertSeeIn('h4:first-child', 'Tipo de Avaliação: '.$evaluation->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Modelo: '.$evaluation->model)
                    ->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                    ->assertVisible('h4:nth-child(3) a[href=\'http://192.168.99.100/evaluations/'.$evaluation->id.'/showContent\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$evaluation->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$evaluation->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$evaluation->updated_at)
                    ->pause(3000);

        });
    }
}
