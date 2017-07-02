<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCaregiverCreateFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $messages = [
            'Username já existente. Escolha outro.',
            'Email já existente. Escolha outro.',
            'O username tem que ser preenchido.',
            'O username tem que ter pelo menos 4 letras ou dígitos.',
            'O email tem que ser válido.',
            'O email tem que ser preenchido.',
            'O nome tem que ser preenchido.',//6
            'O nome tem que ter pelo menos 4 letras.',
            'A localização tem que ser preenchida.',
            'A localização tem que ter pelo menos 4 letras.',
            'O local de trabalho tem que ser preenchido.',
            'O local de trabalho tem que ter pelo menos 4 letras.',
            'A profissão tem que ser preenchida.',
            'A profissão tem que ter pelo menos 4 letras.',
            'A password tem que ser preenchida.',
            'A password tem que ter pelo menos 6 letras ou digitos.',
            'As passwords têm que ser iguais nos dois campos.',
        ];

        $caregiver = [
            'caregiver',
            'Caregiver',
            'caregiver@mail.com',
            'Leiria'
        ];
        
        $this->browse(function (Browser $browser) use ($messages, $caregiver) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->clickLink('Novo Cuidador')
                    ->assertPathIs('/users/create/caregiver')
                    ->press('Criar')
                    ->assertPathIs('/users/create/caregiver')
                    ->assertSee($messages[2])
                    ->assertSee($messages[5])
                    ->assertSee($messages[6])
                    ->assertSee($messages[8])
                    ->assertSee($messages[14])
                    ->type('username', 'a')
                    ->type('name', 'b')
                    ->type('email', 'c@g')
                    ->type('location', 'd')
                    ->type('password', 'f')
                    ->type('password_confirmation', 'g')
                    ->press('Criar')
                    ->assertPathIs('/users/create/caregiver')
                    ->assertSee($messages[3])
                    ->assertSee($messages[7])
                    ->assertSee($messages[4])
                    ->assertSee($messages[9])
                    ->assertSee($messages[15])
                    ->assertSee($messages[16])
                    ->type('username', $caregiver[0])
                    ->type('name', $caregiver[1])
                    ->type('email', $caregiver[2])
                    ->type('location', $caregiver[3])
                    ->type('password', 'aaaaaa')
                    ->type('password_confirmation', 'aaaaaa')
                    ->press('Criar')
                    ->assertPathIs('/users/create/caregiver')
                    ->assertSee($messages[0])
                    ->assertSee($messages[1])
                    ->pause(3000);
        
        });
    }
}
