<?php

namespace Tests\Browser;

use App\Caregiver;
use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCaregiverDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->click('Utilizadores')
                    ->assertPathIs('/users')
                    ->assertSeeIn('select[name=\'userRole\'] option:first-child','Todos')
                    ->click('select[name=\'userRole\'] option:last-child','Cuidador')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/15\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/users/15\']', 'Detalhes')
                    ->assertPathIs('/users/15')
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
                    ->assertSeeIn('div.panel-body','Utentes')
                    ->assertSeeIn('div.panel-body','Materiais')
                    ->assertSeeIn('div.panel-body','Avaliações')
                    ->assertSeeIn('div.panel-body','Voltar Atrás');
 
        });
    }
}
