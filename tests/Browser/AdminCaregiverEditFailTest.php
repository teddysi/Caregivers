<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCaregiverEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testExample()
    {

        $messages = [
            'Email já existente. Escolha outro.',
            'O email tem que ser válido.',
            'O email tem que ser preenchido.',
            'O nome tem que ser preenchido.',//3
            'O nome tem que ter pelo menos 4 letras.',
            'A localização tem que ser preenchida.',
            'A localização tem que ter pelo menos 4 letras.',
            'A password tem que ter pelo menos 6 letras ou digitos.',
            'As passwords têm que ser iguais nos dois campos.',
        ];

        $caregiver = [
            'Admin',
            'admin@mail.com',
            'Leiria'
        ];


        $this->browse(function (Browser $browser) use ($messages, $caregiver) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->assertSeeIn('select[name=\'userRole\'] option:first-child','Todos')
                    ->click('select[name=\'userRole\'] option:last-child','Cuidador')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->visit('/users/15/edit')
                    ->type('name', ' ')
                    ->type('email', ' ')
                    ->type('location', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/users/15/edit')
                    ->assertSee($messages[3])
                    ->assertSee($messages[2])
                    ->assertSee($messages[5])
                    ->type('name', 'b')
                    ->type('email', 'c@g')
                    ->type('location', 'd')
                    ->press('Guardar')
                    ->assertPathIs('/users/15/edit')
                    ->assertSee($messages[1])
                    ->assertSee($messages[6])
                    ->assertSee($messages[4])
                    ->type('password', 'a')
                    ->type('password_confirmation', 'b')
                    ->press('Guardar')
                    ->assertPathIs('/users/15/edit')
                    ->assertSee($messages[7])
                    ->assertSee($messages[8])
                    ->type('name', $caregiver[0])
                    ->type('email', $caregiver[1])
                    ->type('location', $caregiver[2])
                    ->press('Guardar')
                    ->assertPathIs('/users/15/edit')
                    ->assertSee($messages[0])
                    ->pause(3000);
        
        });
    }
}
