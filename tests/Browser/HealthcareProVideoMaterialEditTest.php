<?php

namespace Tests\Browser;

use Storage;
use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProVideoMaterialEditTest extends DuskTestCase
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
            'test description'
        ];

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        $material = Material::find(11);

        $this->browse(function (Browser $browser) use ($material, $fields_to_update, $storagePath)  {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(4)','Video')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table ', $material->name)
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/materials/'.$material->id.'/edit\']', 'Editar')
                    ->assertPathIs('/'.'materials/11/edit')
                    ->assertInputValue('name', $material->name)
                    ->assertInputValue('description', $material->description)
                    ->type('name', $fields_to_update[0])
                    ->type('description', $fields_to_update[1])
                    ->attach('pathVideo', $storagePath.'/videos/Video-2.mp4')
                    ->press('Guardar')
                    ->assertPathIs('/materials')
                    ->pause(2000);

            $material = Material::find(11);
            $material->type = "Video";

            if($material->name != $fields_to_update[0] || $material->description != $fields_to_update[1] || $material->path != 'videos/test name.mp4') {
                $this->assertTrue(false);
            }

                    
            $browser->assertSeeIn('table ', $material->name)
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
