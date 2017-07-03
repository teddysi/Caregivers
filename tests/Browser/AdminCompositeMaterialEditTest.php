<?php

namespace Tests\Browser;

use DB;
use App\Admin;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCompositeMaterialEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group admin
     */
    public function testBasicExample()
    {

        $fields_to_update = [
            'test name',
            'test description'
        ];

        $material = Material::find(26);

        $this->browse(function (Browser $browser) use ($material, $fields_to_update)  {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:last-child','Composto')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table ', $material->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'materials/26/edit')
                    ->assertInputValue('name', $material->name)
                    ->assertInputValue('description', $material->description)
                    ->type('name', $fields_to_update[0])
                    ->type('description', $fields_to_update[1])
                    ->press('Guardar')
                    ->assertPathIs('/materials')
                    ->pause(2000);

            $material = Material::find(26);
            $material->type = "Composto";

            if($material->name != $fields_to_update[0] || $material->description != $fields_to_update[1]) {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $material->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000)
                    ->click('a[href=\'http://192.168.99.100/materials/26/materials\']', 'Materiais')
                    ->assertPathIs('/materials/26/materials')
                    ->assertSeeIn('.materials-to-associate tr:first-child td:last-child button', 'Adicionar')
                    ->click('.materials-to-associate tr:first-child td:last-child button', 'Adicionar')
                    ->assertPathIs('/'.'materials/26/materials')
                    ->assertSeeIn('.materials-associated', '4')
                    ->clickLink('Concluído')
                    ->assertPathIs('/'.'materials')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes');


            $material1 = $material->materials->get(0);
            $material2 = $material->materials->get(1);
            $material3 = $material->materials->get(2);
            $material4 = $material->materials->get(3);

            $browser->assertSeeIn('table ', $material1->name)
                    ->assertSeeIn('table ', $material2->name)
                    ->assertSeeIn('table ', $material3->name)
                    ->assertSeeIn('table ', $material4->name);

        });
    }
}
