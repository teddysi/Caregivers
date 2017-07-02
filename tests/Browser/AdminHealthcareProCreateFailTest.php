<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminHealthcareProCreateFailTest extends DuskTestCase
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

        $healthcarepro = [
            'healthcarePro',
            'HealthcarePro',
            'healthcarePro@mail.com',
            'Medic',
            'China'
        ];
        
        $this->browse(function (Browser $browser) use ($messages, $healthcarepro) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->clickLink('Novo Profissional de Saúde')
                    ->assertPathIs('/users/create/healthcarepro')
                    ->press('Criar')
                    ->assertPathIs('/users/create/healthcarepro')
                    ->assertSee($messages[2])
                    ->assertSee($messages[5])
                    ->assertSee($messages[6])
                    ->assertSee($messages[10])
                    ->assertSee($messages[12])
                    ->assertSee($messages[14])
                    ->type('username', 'a')
                    ->type('name', 'b')
                    ->type('email', 'c@g')
                    ->type('job', 'b')
                    ->type('facility', 'b')
                    ->type('password', 'f')
                    ->type('password_confirmation', 'g')
                    ->press('Criar')
                    ->assertPathIs('/users/create/healthcarepro')
                    ->assertSee($messages[3])
                    ->assertSee($messages[7])
                    ->assertSee($messages[4])
                    ->assertSee($messages[11])
                    ->assertSee($messages[13])
                    ->assertSee($messages[15])
                    ->assertSee($messages[16])
                    ->type('username', $healthcarepro[0])
                    ->type('name', $healthcarepro[1])
                    ->type('email', $healthcarepro[2])
                    ->type('job', $healthcarepro[3])
                    ->type('facility', $healthcarepro[4])
                    ->type('password', 'aaaaaa')
                    ->type('password_confirmation', 'aaaaaa')
                    ->press('Criar')
                    ->assertPathIs('/users/create/healthcarepro')
                    ->assertSee($messages[0])
                    ->assertSee($messages[1])
                    ->pause(3000);
        
        });
    }
}
