<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProTextMaterialEditTest extends DuskTestCase
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
            'test name',
            'test description',
            'test text text test text test text'
        ];

        $material = Material::find(1);

        $this->browse(function (Browser $browser) use ($material, $fields_to_update)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(2)','Texto')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table ', $material->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'materials/1/edit')
                    ->assertInputValue('name', $material->name)
                    ->assertInputValue('description', $material->description)
                    ->assertInputValue('body', $material->body)
                    ->type('name', $fields_to_update[0])
                    ->type('description', $fields_to_update[1])
                    ->type('body', $fields_to_update[2])
                    ->press('Guardar')
                    ->assertPathIs('/materials')
                    ->pause(2000);

            $material = Material::find(1);
            $material->type = "Texto";

            if($material->name != $fields_to_update[0] || $material->description != $fields_to_update[1] || $material->body != $fields_to_update[2]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $material->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/1\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/1\']', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'Texto: '.$material->body)
                    ->assertSeeIn('h4:nth-child(4)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);
        });
    }
}
