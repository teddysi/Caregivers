<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAdminCreateFailTest extends DuskTestCase
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
            'Username já existente. Escolha outro.',//0
            'Email já existente. Escolha outro.',//1
            'O username tem que ser preenchido.',//2
            'O username tem que ter pelo menos 4 letras ou dígitos.',//3
            'O email tem que ser válido.',//4
            'O email tem que ser preenchido.',//5
            'O nome tem que ser preenchido.',//6
            'O nome tem que ter pelo menos 4 letras.',//7
            'A localização tem que ser preenchida.',//8
            'A localização tem que ter pelo menos 4 letras.',//9
            'O local de trabalho tem que ser preenchido.',//10
            'O local de trabalho tem que ter pelo menos 4 letras.',//11
            'A profissão tem que ser preenchida.',//12
            'A profissão tem que ter pelo menos 4 letras.',//13
            'A password tem que ser preenchida.',//14
            'A password tem que ter pelo menos 6 letras ou digitos.',//15
            'As passwords têm que ser iguais nos dois campos.',//16
        ];

        $admin = [
            'admin',
            'Admin',
            'admin@mail.com',
        ];
        
        $this->browse(function (Browser $browser) use ($messages, $admin) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->clickLink('Novo Administrador')
                    ->assertPathIs('/users/create/admin')
                    ->press('Criar')
                    ->assertPathIs('/users/create/admin')
                    ->assertSee($messages[2])
                    ->assertSee($messages[5])
                    ->assertSee($messages[6])
                    ->assertSee($messages[14])
                    ->type('username', 'a')
                    ->type('name', 'b')
                    ->type('email', 'c@g')
                    ->type('password', 'f')
                    ->type('password_confirmation', 'g')
                    ->press('Criar')
                    ->assertPathIs('/users/create/admin')
                    ->assertSee($messages[3])
                    ->assertSee($messages[7])
                    ->assertSee($messages[4])
                    ->assertSee($messages[15])
                    ->assertSee($messages[16])
                    ->type('username', $admin[0])
                    ->type('name', $admin[1])
                    ->type('email', $admin[2])
                    ->type('location', $admin[3])
                    ->type('password', 'aaaaaa')
                    ->type('password_confirmation', 'aaaaaa')
                    ->press('Criar')
                    ->assertPathIs('/users/create/admin')
                    ->assertSee($messages[0])
                    ->assertSee($messages[1])
                    ->pause(3000);
        
        });
    }
}
