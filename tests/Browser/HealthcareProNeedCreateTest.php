<?php

namespace Tests\Browser;

use App\Need;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProNeedCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $loginTest = new SuccessfullyLoginTest();
        $loginTest->testBasicExample();

        $need_count = count(Need::all());

        $this->browse(function (Browser $browser) use ($need_count) {
            $browser->clickLink('Necessidades')
                    ->assertPathIs('/caregivers/public/needs')
                    ->clickLink('Nova Necessidade')
                    ->assertPathIs('/caregivers/public/needs/create')
                    ->type('description', 'test12345')
                    ->press('Criar')
                    ->assertPathIs('/caregivers/public/needs');

            $needs_count_new = count(Need::all());
            $new_need = Need::find($needs_count_new);
            
            if($need_count + 1 !== $needs_count_new) {
                $this->assertFalse(true);
            }
            

            $browser->assertSeeIn('table tr:first-child td:first-child', $new_need->description)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_need->creator->username)
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Materiais')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/caregivers/public/needs/'.$new_need->id)
                    ->assertSeeIn('h2', 'Necessidade: '.$new_need->description)
                    ->assertSeeIn('h4:first-child', 'Criador: '.$new_need->creator->username)
                    ->assertSeeIn('h4:nth-child(2)', 'Data da criação: '.(string)$new_need->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$new_need->updated_at)
                    ->pause(2000);
        });
    }
}
