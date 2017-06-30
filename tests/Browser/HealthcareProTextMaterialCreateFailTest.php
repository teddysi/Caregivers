<?php

namespace Tests\Browser;

use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProTextMaterialCreateFailTest extends DuskTestCase
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
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->clickLink('Materiais')
                    ->assertPathIs('/materials')
                    ->clickLink('Novo Texto')
                    ->assertPathIs('/materials/create/text')
                    ->type('name', $material->name)
                    ->type('description', $material->description)
                    ->type('body', $material->body)
                    ->press('Criar')
                    ->assertPathIs('/materials/create/text')
                    ->assertSee($messages[0])
                    ->type('name', ' ')
                    ->type('description', ' ')
                    ->type('body', ' ')
                    ->press('Criar')
                    ->assertPathIs('/materials/create/text')
                    ->assertSee($messages[1])
                    ->assertSee($messages[3])
                    ->assertSee($messages[5])
                    ->type('name', 'a')
                    ->type('description', 'b')
                    ->type('body', ' ')
                    ->press('Criar')
                    ->assertPathIs('/materials/create/text')
                    ->assertSee($messages[2])
                    ->assertSee($messages[4])
                    ->assertSee($messages[5])
                    ->pause(2000);

        });
    }
}
