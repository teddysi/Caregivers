<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminProfileTest extends DuskTestCase
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
                    ->clickLink('Perfil')
                    ->assertPathIs('/users/13/profile');
            $user = Admin::find(13);

            $browser->assertSeeIn('h2', 'Utilizador: '.$user->username)
                    ->assertSeeIn('h4:first-child', 'Nome: '.$user->name)
                    ->assertSeeIn('h4:nth-child(2)', 'Email: '.$user->email)
                    ->assertSeeIn('h4:nth-child(3)', 'Função: Administrador')
                    ->assertSeeIn('h4:nth-child(4)', 'Data da criação: '.(string)$user->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$user->updated_at)
                    ->pause(2000);
        });
    }
}
