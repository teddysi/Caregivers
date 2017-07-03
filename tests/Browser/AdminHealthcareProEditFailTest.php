<?php

namespace Tests\Browser;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminHealthcareProEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testExample()
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

        $healthcarePro = [
            'HealthcarePro',
            'admin@mail.com',
        ];

        $this->browse(function (Browser $browser) use ($messages, $healthcarePro) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Utilizadores')
                    ->assertPathIs('/users')
                    ->assertSeeIn('select[name=\'userRole\'] option:first-child','Todos')
                    ->click('select[name=\'userRole\'] option:nth-child(3)','Profissional de Saúde')
                    ->press('Procurar')
                    ->assertPathIs('/users')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/users/14/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/users/14/edit\']', 'Editar')
                    ->assertPathIs('/users/14/edit')
                    ->type('name', ' ')
                    ->type('email', ' ')
                    ->type('job', ' ')
                    ->type('facility', '')
                    ->press('Guardar')
                    ->assertPathIs('/users/14/edit')
                    ->assertSee($messages[5])
                    ->assertSee($messages[6])
                    ->assertSee($messages[10])
                    ->assertSee($messages[12])
                    ->type('name', 'b')
                    ->type('email', 'c@g')
                    ->type('job', 'a')
                    ->type('facility', 'v')
                    ->press('Guardar')
                    ->assertPathIs('/users/14/edit')
                    ->assertSee($messages[7])
                    ->assertSee($messages[4])
                    ->assertSee($messages[11])
                    ->assertSee($messages[13])
                    ->type('password', 'a')
                    ->type('password_confirmation', 'b')
                    ->press('Guardar')
                    ->assertPathIs('/users/14/edit')
                    ->assertSee($messages[15])
                    ->assertSee($messages[16])
                    ->type('name', $healthcarePro[0])
                    ->type('email', $healthcarePro[1])
                    ->press('Guardar')
                    ->assertPathIs('/users/14/edit')
                    ->assertSee($messages[1])
                    ->pause(3000);
        
        });
    }
}
