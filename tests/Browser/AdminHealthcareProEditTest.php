<?php

namespace Tests\Browser;

use App\Admin;
use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminHealthcareProEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {

        $this->browse(function (Browser $browser) use () {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->click('Utilizadores')
                    ->assertPathIs('/users')
                    ->assertSeeIn('select[name=\'userRole\'] option:first-child','Todos')
                    ->click('select[name=\'userRole\'] option:nth-child(3)','Profissional de Saúde')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/14\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/users/14\']', 'Editar')
                    ->assertPathIs('/users/14/edit')
                    ->type('name', 'Im a Test')
                    ->type('email', 'tessssst@gmail.com')
                    ->type('job', 'Medicccc')
                    ->type('facility', 'China')
                    ->press('Guardar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/14\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/users/14\']', 'Detalhes')
                    ->assertPathIs('/users/14')
                    ->pause(3000);

            $healthcarePro = HealthcarePro::find(13);

            if($healthcarePro->name != 'Im a Test' || $healthcarePro->email != 'tessssst@gmail.com' || $healthcarePro->job != 'Medicccc' || $healthcarePro->facility != 'China') {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('h2', 'Utilizador: '.$healthcarePro->username)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$healthcarePro->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$healthcarePro->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Profissional de Saúde')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Data da criação: '.(string)$healthcarePro->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$healthcarePro->updated_at)
                    ->pause(2000);
        
        });
    }
}
