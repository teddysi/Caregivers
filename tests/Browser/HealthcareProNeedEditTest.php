<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Need;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProNeedEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'test description'
        ];

        $need = Need::find(1);

        $this->browse(function (Browser $browser) use ($need, $fields_to_update)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Necessidades')
                    ->assertPathIs('/needs')
                    ->assertSeeIn('table ', $need->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/needs/'.$need->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/needs/'.$need->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'needs/'.$need->id.'/edit')
                    ->assertInputValue('description', $need->description)
                    ->type('description', $fields_to_update[0])
                    ->press('Guardar')
                    ->assertPathIs('/needs')
                    ->pause(2000);

            $need = need::find(1);

            if($need->description != $fields_to_update[0]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $need->description)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/needs/1\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/needs/1\']', 'Detalhes')
                    ->assertPathIs('/'.'needs/'.$need->id)
                    ->assertSeeIn('h2', 'Necessidade: '.$need->description)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$need->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$need->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$need->updated_at)
                    ->pause(2000);
        });
    }
}
