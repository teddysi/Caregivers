<?php

namespace Tests\Browser;

use App\Admin;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTextMaterialEditFailTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $messages = [
            'Esse nome já existe. Escolha outro.',
            'O nome tem que ser preenchido.',
            'O nome tem que ser maior que 4 letras.',
            'A descrição tem que ser preenchida.',
            'A descrição tem que ser maior que 4 letras.',
            'O campo texto não pode ser vazio.',
        ];

        $material = Material::find(1);

        $this->browse(function (Browser $browser) use ($messages, $material) {
            $browser->loginAs(Admin::find(13))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(2)','Texto')
                    ->press('Procurar')
                    ->assertPathIs('/materials')
                    ->assertSeeIn('a[href=\'http://192.168.99.100/materials/2/edit\']', 'Editar')
                    ->click('a[href=\'http://192.168.99.100/materials/2/edit\']', 'Editar')
                    ->assertPathIs('/materials/2/edit')
                    ->type('name', $material->name)
                    ->type('description', $material->description)
                    ->type('body', $material->body)
                    ->press('Guardar')
                    ->assertPathIs('/materials/2/edit')
                    ->assertSee($messages[0])
                    ->type('name', ' ')
                    ->type('description', ' ')
                    ->type('body', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/materials/2/edit')
                    ->assertSee($messages[1])
                    ->assertSee($messages[3])
                    ->assertSee($messages[5])
                    ->type('name', 'a')
                    ->type('description', 'b')
                    ->type('body', ' ')
                    ->press('Guardar')
                    ->assertPathIs('/materials/2/edit')
                    ->assertSee($messages[2])
                    ->assertSee($messages[4])
                    ->assertSee($messages[5])
                    ->pause(2000);

        });
    }
}
