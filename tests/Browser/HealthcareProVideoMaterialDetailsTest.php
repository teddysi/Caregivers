<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProVideoMaterialDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $material = Material::find(11);
        $material->type = 'Video';

        $this->browse(function (Browser $browser) use ($material) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Recursos')
                    ->assertSee('Materiais')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(4)','Video')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'Video')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/11\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/11\']', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                    ->assertVisible('video')
                    ->assertSeeIn('h4:nth-child(5)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(6)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);
        });
    }
}
