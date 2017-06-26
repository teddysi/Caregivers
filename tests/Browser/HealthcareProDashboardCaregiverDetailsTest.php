<?php

namespace Tests\Browser;

use App\Caregiver;
use App\Log;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProDashboardCaregiverDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $this->browse(function (Browser $browser) {
            $browser->assertSee('Caregiver')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/caregivers/public/users/15')
                    ->assertSeeIn('h2', 'Utilizador: caregiver')
                    ->assertSeeIn('div.details h4:first-child', 'Nome: Caregiver')
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: caregiver@mail.com')
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Cuidador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Localização: Leiria')
                    ->assertSeeIn('div.details h4:nth-child(5)', 'Nº Profissionais de Saúde: 1/2');

            $caregiver = Caregiver::find(15);

            $browser->assertSeeIn('div.details h4:nth-child(6)', 'Criador: '.$caregiver->creator->username)
                    ->assertSeeIn('div.details h4:nth-child(7)', 'Data da criação: '.(string)$caregiver->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$caregiver->updated_at)
                    ->assertSeeIn('div.panel-heading h3', 'Ações')
                    ->assertSeeIn('div.panel-body','Editar')
                    ->assertSeeIn('div.panel-body','Bloquear')
                    ->assertSeeIn('div.panel-body','Pacientes')
                    ->assertSeeIn('div.panel-body','Materiais')
                    ->assertSeeIn('div.panel-body','Avaliações')
                    ->assertSeeIn('div.panel-body','Voltar Atrás');

            if(!count($caregiver->logs)) {
                $browser->assertSee('Registos')
                        ->assertSee('Não existem registos referentes a este Cuidador.');
            } else {
                $browser->assertDontSee('Não existem registos referentes a este Cuidador.');
                $i = 1;
                foreach ($caregiver->logs as $log) {
                    if( $i == 1) {
                        $browser->assertSeeIn('table.tasks tr:first-child td:first-child', $log->performed_task)
                                ->assertSeeIn('table.tasks tr:first-child td:nth-child(2)', $log->doneBy->username)
                                ->assertSeeIn('table.tasks tr:first-child td:last-child', (string)$log->created_at);
                    } else if( $i == count($caregiver->logs) && count($caregiver->logs) > 1) {
                        $browser->assertSeeIn('table.tasks tr:last-child td:first-child', $log->performed_task)
                                ->assertSeeIn('table.tasks tr:last-child td:nth-child(2)', $log->doneBy->username)
                                ->assertSeeIn('table.tasks tr:last-child td:last-child', (string)$log->created_at);
                    } else {
                        $browser->assertSeeIn('table.tasks tr:nth-child('.$i.') td:first-child', $log->performed_task)
                                ->assertSeeIn('table.tasks tr:nth-child('.$i.') td:nth-child(2)', $log->doneBy->username)
                                ->assertSeeIn('table.tasks tr:nth-child('.$i.') td:last-child', (string)$log->created_at);
                    }
                    $i++;
                }
            } 
        });
    }
}
