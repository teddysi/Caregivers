<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\SuccessfullyLoginTest;
use App\User;

class SuccessfullyUpdateCaregiverTeste extends DuskTestCase
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

        $user = User::find(15);

        $this->browse(function (Browser $browser) use ($user)  {
            $browser->assertSeeIn('a.user_update_'.$user->id.'_button', 'Editar')
                    ->click('a.user_update_'.$user->id.'_button')
                    ->assertPathIs('/caregivers/public/users/'.$user->id.'/edit')
                    ->type('name', 'test')
                    ->type('email', 'test@gmail.com')
                    ->type('location', 'test')
                    ->press('Guardar')
                    ->assertPathIs('/caregivers/public/users');

            $user = User::find(15);
                    
            $browser->assertSeeIn('table.users_table', $user->name)
                    ->assertSeeIn('a.user_details_'.$user->id.'_button', 'Detalhes')
                    ->click('a.user_details_'.$user->id.'_button')
                    ->assertPathIs('/caregivers/public/users/'.$user->id)
                    ->pause(5000);
        });
    }
}
