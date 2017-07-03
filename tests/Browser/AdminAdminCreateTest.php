<?php

namespace Tests\Browser;

use App\Admin;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAdminCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {
        
        $users_count = count(User::all());

        $this->browse(function (Browser $browser) use ($users_count) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->clickLink('Novo Administrador')
                    ->assertPathIs('/users/create/admin')
                    ->type('username', 'test')
                    ->type('name', 'test')
                    ->type('email', 'test@gmail.com')
                    ->type('password', 'testss')
                    ->type('password_confirmation', 'testss')
                    ->press('Criar')
                    ->assertPathIs('/users');

            $users_count_new = count(User::all());
            $new_user = User::find($users_count_new);
            
            if($users_count + 1 !== $users_count_new) {
                $this->assertFalse(true);
            }
            

            $browser->assertSeeIn('table tr:first-child td:first-child', $new_user->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_user->email)
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child button:first-child', 'Bloquear')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/'.'users/'.$new_user->id)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$new_user->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$new_user->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Administrador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Data da criação: '.(string)$new_user->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$new_user->updated_at)
                    ->pause(5000);
        });
    }
}
