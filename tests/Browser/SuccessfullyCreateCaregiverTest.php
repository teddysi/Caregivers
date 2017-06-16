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

        
        

        $this->browse(function (Browser $browser) {
            $browser->click('a.nav_caregivers')
                    ->assertPathIs('/caregivers/public/users')
                    ->click('a.create_caregiver')
                    ->assertPathIs('/caregivers/public/users/create/caregiver')
                    ->type('username', 'test')
                    ->type('name', 'test')
                    ->type('email', 'test@gmail.com')
                    ->type('location', 'test')
                    ->type('password', 'testss')
                    ->type('password_confirmation', 'testss')
                    ->press('Criar')
                    ->assertPathIs('/caregivers/public/users');

            $users_count = count(User::all());
            $new_user = User::find($users_count);
            
            if($new_user->username !== 'test') {
                $browser->assertSee('Error Creating Caregiver. DB not updated!!');
            }
            

            $browser->assertSeeIn('table.users_table', $new_user->name)
                    ->assertSeeIn('a.user_details_'.$new_user->id.'_button', 'Detalhes')
                    ->click('a.user_details_'.$new_user->id.'_button')
                    ->assertPathIs('/caregivers/public/users/'.$new_user->id)
                    ->pause(5000);
        });
    }
}
