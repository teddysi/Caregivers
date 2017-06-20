<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\SuccessfullyLoginTest;
use App\User;

class SuccessfullyUpdateCaregiverTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $login = new SuccessfullyLoginTest();
        $login->testBasicExample();

        $fields_to_update = [
            'test',
            'test@gmail.com',
            'test'
        ];

        $user = User::find(15);

        $this->browse(function (Browser $browser) use ($user, $fields_to_update)  {
            $browser->assertSeeIn('table tr:first-child td:first-child', $user->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $user->email)
                    ->assertSeeIn('table tr:first-child td:last-child .btn-primary', 'Detalhes')
                    ->click('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertPathIs('/caregivers/public/users/'.$user->id.'/edit')
                    ->type('name', $fields_to_update[0])
                    ->type('email', $fields_to_update[1])
                    ->type('location', $fields_to_update[2])
                    ->press('Guardar')
                    ->assertPathIs('/caregivers/public/users')
                    ->pause(2000);

            $user = User::find(15);

            if($user->name != $fields_to_update[0] || $user->email != $fields_to_update[1] || $user->location != $fields_to_update[2]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $user->name)
                    ->assertSeeIn('table ', $user->email)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/caregivers/public/users/15\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/caregivers/public/users/15\']', 'Detalhes')
                    ->assertPathIs('/caregivers/public/users/'.$user->id)
                    ->assertSeeIn('div.details h4:first-child', 'Nome: '.$user->name)
                    ->assertSeeIn('div.details h4:nth-child(2)', 'Email: '.$user->email)
                    ->assertSeeIn('div.details h4:nth-child(3)', 'Função: Cuidador')
                    ->assertSeeIn('div.details h4:nth-child(4)', 'Localização: '.$user->location)
                    ->assertSeeIn('div.details h4:nth-child(5)', 'Nº Profissionais de Saúde: 1/2')
                    ->pause(5000);
        });
    }
}
