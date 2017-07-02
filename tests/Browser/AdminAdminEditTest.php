<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAdminEditTest extends DuskTestCase
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
                    ->click('select[name=\'userRole\'] option:nth-child(2)','Administrador')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/13\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/users/13\']', 'Editar')
                    ->assertPathIs('/users/13/edit')
                    ->type('name', 'Im a Test')
                    ->type('email', 'tessssst@gmail.com')
                    ->press('Guardar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/13\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/users/13\']', 'Detalhes')
                    ->assertPathIs('/users/13')
                    ->pause(3000);

            $admin = Admin::find(13);

            if($admin->name != 'Im a Test' || $admin->email != 'tessssst@gmail.com') {
                $this->assertTrue(false);
            }

            $browser->assertSeeIn('h2', 'Utilizador: '.$admin->username)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$admin->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$admin->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Administrador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Data da criação: '.(string)$admin->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$admin->updated_at)
                    ->pause(2000);
        
        });
    }
}
