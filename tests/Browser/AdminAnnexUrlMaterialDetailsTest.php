<?php

namespace Tests\Browser;

use App\Material;
use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAnnexUrlMaterialDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {

        $material = Material::find(24);
        $material->type = 'Anexo';

        $this->browse(function (Browser $browser) use ($material) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(6)','Anexo')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'Anexo')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/24\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/24\']', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'URL:')
                    ->assertSeeIn('h4:nth-child(3) a', $material->url)
                    ->assertVisible('h4:nth-child(3) a[href=\''.$material->url.'\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);
        });
    }
}
