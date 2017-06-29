<?php

namespace Tests\Browser;

use App\Evaluation;
use Storage;
use App\User;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class HealthcareProCaregiverEvaluationCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
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
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/15\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/users/15\']', 'Detalhes')
                    ->assertPathIs('/users/15')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/15/rate\']', 'Avaliações')
                    ->click('a[href=\'http://192.168.99.100/caregivers/15/rate\']', 'Avaliações')
                    ->assertPathIs('/caregivers/15/rate')
                    ->assertSee('Nova Avaliação')
                    ->clickLink('Nova Avaliação')
                    ->assertPathIs('/caregivers/15/evaluations/create/eval')
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
                    ->assertPathIs('/caregivers/15/rate')
                    ->assertSeeIn('table.evaluations tr:first-child td:first-child', $new_evaluation[0])
                    ->assertSeeIn('table.evaluations tr:first-child td:nth-child(2)', $new_evaluation[1])
                    ->assertSeeIn('table.evaluations tr:first-child td:nth-child(3)', $new_evaluation[2])
                    ->assertSeeIn('table.evaluations tr:first-child td:nth-child(4)', 'healthcarePro')
                    ->pause(2000);

            $evaluations_count = count(Evaluation::all());
            $evaluation = Evaluation::find($evaluations_count);

            if($evaluation->description != $new_evaluation[0]) {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('table.evaluations tr:first-child td:nth-child(5)', (string)$evaluation->created_at)
                    ->click('table.evaluations tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSee('This is a test.jpg')
                    ->pause(3000);

        });
    }

}
