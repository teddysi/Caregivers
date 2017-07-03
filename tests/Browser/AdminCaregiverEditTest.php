<?php

namespace Tests\Browser;

use App\Admin;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCaregiverEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'test',
            'test@gmail.com',
            'test'
        ];

        $user = User::find(15);

        $this->browse(function (Browser $browser) use ($user, $fields_to_update)  {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->visit('/users/15/edit')
                    ->assertPathIs('/'.'users/'.$user->id.'/edit')
                    ->assertInputValue('name', $user->name)
                    ->assertInputValue('email', $user->email)
                    ->assertInputValue('location', $user->location)
                    ->type('name', $fields_to_update[0])
                    ->type('email', $fields_to_update[1])
                    ->type('location', $fields_to_update[2])
                    ->press('Guardar')
                    ->assertPathIs('/users')
                    ->pause(2000);

            $user = User::find(15);

            if($user->name != $fields_to_update[0] || $user->email != $fields_to_update[1] || $user->location != $fields_to_update[2]) {
                $this->assertTrue(false);
            }

                    
            $browser->visit('/users/15')
                    ->assertPathIs('/'.'users/'.$user->id)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$user->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$user->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Cuidador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Localização: '.$user->location)
                    ->assertSeeIn('div.details h4:nth-child(5)', 'Nº Profissionais de Saúde: 1/2')
                    ->assertSeeIn('div.details h4:nth-child(6)', 'Criador: '.$user->creator->username)
                    ->assertSeeIn('div.details h4:nth-child(7)', 'Data da criação: '.(string)$user->created_at)
                    ->assertSeeIn('div.details h4:last-child', 'Data da última atualização: '.(string)$user->updated_at)
                    ->pause(5000);
        });
    }
}
