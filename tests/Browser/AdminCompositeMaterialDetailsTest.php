<?php

namespace Tests\Browser;

use App\Admin;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCompositeMaterialDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $material = Material::find(26);
        $material->type = 'Composto';

        $this->browse(function (Browser $browser) use ($material) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Recursos')
                    ->assertSee('Materiais')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:last-child','Composto')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'Composto')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes')
                    ->click('a[href=\'http://192.168.99.100/materials/26\']', 'Detalhes')
                    ->assertPathIs('/'.'materials/'.$material->id)
                    ->assertSeeIn('h2', 'Material: '.$material->name)
                    ->assertSeeIn('h4:first-child', 'Tipo: '.$material->type)
                    ->assertSeeIn('h4:nth-child(2)', 'Descrição: '.$material->description)
                    ->assertSeeIn('h4:nth-child(3)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(4)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);


            $material1 = $material->materials->get(0);
            $this->changeTypeFormat($material1);
            $material2 = $material->materials->get(1);
            $this->changeTypeFormat($material2);
            $material3 = $material->materials->get(2);
            $this->changeTypeFormat($material3);

            $browser->assertSeeIn('table tr:first-child td:first-child', '1')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', $material1->name)
                    ->assertSeeIn('table tr:first-child td:nth-child(3)', $material1->type)
                    ->assertSeeIn('table tr:first-child td:nth-child(4)', $material1->creator->username)
                    ->assertSeeIn('table tr:first-child td:last-child a.btn-primary','Detalhes')
                    ->assertSeeIn('table tr:first-child td:last-child a.btn-warning','Editar')
                    ->assertSeeIn('table tr:first-child td:last-child button.btn-danger','Bloquear')
                    ->assertSeeIn('table tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('table tr:nth-child(2) td:nth-child(2)', $material3->name)
                    ->assertSeeIn('table tr:nth-child(2) td:nth-child(3)', $material3->type)
                    ->assertSeeIn('table tr:nth-child(2) td:nth-child(4)', $material3->creator->username)
                    ->assertSeeIn('table tr:nth-child(2) td:last-child a.btn-primary','Detalhes')
                    ->assertSeeIn('table tr:nth-child(2) td:last-child a.btn-warning','Editar')
                    ->assertSeeIn('table tr:nth-child(2) td:last-child button.btn-danger','Bloquear')
                    ->assertSeeIn('table tr:last-child td:first-child', '3')
                    ->assertSeeIn('table tr:last-child td:nth-child(2)', $material2->name)
                    ->assertSeeIn('table tr:last-child td:nth-child(3)', $material2->type)
                    ->assertSeeIn('table tr:last-child td:nth-child(4)', $material2->creator->username)
                    ->assertSeeIn('table tr:last-child td:last-child a.btn-primary','Detalhes')
                    ->assertSeeIn('table tr:last-child td:last-child a.btn-warning','Editar')
                    ->assertSeeIn('table tr:last-child td:last-child button.btn-danger','Bloquear');

        });
    }

    public static function changeTypeFormat($material)
    {
        switch ($material->type) {
            case 'text':
                $material->type = 'Texto';
                break;

            case 'image':
                $material->type = 'Imagem';
                break;

            case 'video':
                $material->type = 'Video';
                break;

            case 'emergencyContact':
                $material->type = 'Contacto de Emergência';
                break;

            case 'annex':
                $material->type = 'Anexo';
                break;

            case 'composite':
                $material->type = 'Composto';
                break;

            default:
                break;
        }
    }
}
