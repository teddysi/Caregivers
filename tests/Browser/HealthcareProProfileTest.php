<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->clickLink('Perfil')
                    ->assertPathIs('/users/14/profile');
            $user = HealthcarePro::find(14);

            $browser->assertSeeIn('h2', 'Utilizador: '.$user->username)
                    ->assertSeeIn('h4:first-child', 'Nome: '.$user->name)
                    ->assertSeeIn('h4:nth-child(2)', 'Email: '.$user->email)
                    ->assertSeeIn('h4:nth-child(3)', 'Função: Profissional de Saúde')
                    ->assertSeeIn('h4:nth-child(4)', 'Trabalho/Estatuto: Médico')
                    ->assertSeeIn('h4:nth-child(5)', 'Local de Trabalho: '.$user->location)
                    ->assertSeeIn('h4:nth-child(6)', 'Data da criação: '.(string)$user->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$user->updated_at)
                    ->pause(2000);
        });
    }
}
