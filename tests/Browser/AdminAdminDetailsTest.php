<?php

namespace Tests\Browser;

use App\Admin;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAdminDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->assertSeeIn('select[name=\'userRole\'] option:first-child','Todos')
                    ->click('select[name=\'userRole\'] option:nth-child(2)','Administrador')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/13\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/users/13\']', 'Detalhes')
                    ->assertPathIs('/users/13');
                    
            $admin = Admin::find(13);

            $browser->assertSeeIn('h2', 'Utilizador: '.$admin->username)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$admin->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$admin->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Administrador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Data da criação: '.(string)$admin->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$admin->updated_at)
                    ->pause(5000);


        });
    }
}
