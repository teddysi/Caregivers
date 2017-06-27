<?php

namespace Tests\Browser;

use App\Material;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProAnnexMaterialDetailsTest extends DuskTestCase
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

        $material = Material::find(21);
        $material->type = 'Anexo';

        $this->browse(function (Browser $browser) use ($material) {
            $browser->clickLink('Recursos')
                    ->assertSee('Materiais')
                    ->clickLink('Materiais')
                    ->assertPathIs('/caregivers/public/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(6)','Anexo')
                    ->press('Procurar')
                    ->assertPathIs('/caregivers/public/materials')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'Anexo')
                    ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->click('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertPathIs('/caregivers/public/materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                    ->assertSeeIn('h4:nth-child(3) a', $material->name.'.pdf')
                    ->assertVisible('h4:nth-child(3) a[href=\'http://192.168.99.100/caregivers/public/materials/'.$material->id.'/showContent\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);
        });
    }
}
