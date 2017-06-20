<?php

namespace Tests\Browser;

use Tests\Browser\ResourcesDropDownTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class SuccessfullyCreateCaregiverTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $resources = new ResourcesDropDownTest();
        $resources->testBasicExample();

        
        $users_count = count(User::all());

        $this->browse(function (Browser $browser) use ($users_count) {
            $browser->clickLink('Cuidadores')
                    ->assertPathIs('/caregivers/public/users')
                    ->clickLink('Novo Cuidador')
                    ->assertPathIs('/caregivers/public/users/create/caregiver')
                    ->type('username', 'test')
                    ->type('name', 'test')
                    ->type('email', 'test@gmail.com')
                    ->type('location', 'test')
                    ->type('password', 'testss')
                    ->type('password_confirmation', 'testss')
                    ->press('Criar')
                    ->assertPathIs('/caregivers/public/users');

            $users_count_new = count(User::all());
            $new_user = User::find($users_count_new);
            
            if($users_count + 1 !== $users_count_new) {
                $this->assertFalse(true);
            }
            

            $browser->assertSeeIn('table tr:first-child td:first-child', $new_user->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_user->name)
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child button:first-child', 'Bloquear')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/caregivers/public/users/'.$new_user->id)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$new_user->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$new_user->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Cuidador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Localização: '.$new_user->location)
                    ->assertSeeIn('div.details h4:nth-child(5)', 'Nº Profissionais de Saúde: 1/2')
                    ->pause(5000);
        });
    }
}
