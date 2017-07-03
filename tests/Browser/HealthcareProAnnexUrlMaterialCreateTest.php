<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProAnnexUrlMaterialCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {
    
        $material_count = count(Material::all());

        $this->browse(function (Browser $browser) use ($material_count) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->clickLink('Novo Anexo')
                    ->assertPathIs('/materials/create/annex')
                    ->click('select optgroup:nth-child(2) option:first-child', 'Video Externo')
                    ->type('name', 'test')
                    ->type('description', 'test description')
                    ->type('url', 'https://www.youtube.com/')
                    ->press('Criar')
                    ->assertPathIs('/materials');

            $material_count_new = count(Material::all());
            $new_material = Material::find($material_count_new);
            $new_material->type = 'Anexo';
            
            if($material_count + 1 !== $material_count_new) {
                $this->assertFalse(true);
            }
            

            $browser->assertSeeIn('table tr:first-child td:first-child', $new_material->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $new_material->type)
                    ->assertSeeIn('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child .btn-warning', 'Editar')
                    ->assertSeeIn('table tr:first-child td:last-child button.btn-danger', 'Bloquear')
                    ->click('table tr:first-child td:last-child a:first-child', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$new_material->id)
                    ->assertSeeIn('h2', 'Material: '.$new_material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$new_material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$new_material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'URL:')
                    ->assertSeeIn('h4:nth-child(3) a', $new_material->url)
                    ->assertVisible('h4:nth-child(3) a[href=\''.$new_material->url.'\']')
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$new_material->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$new_material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$new_material->updated_at)
                    ->pause(2000);
        });
    }
}
