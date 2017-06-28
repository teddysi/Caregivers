<?php

namespace Tests\Browser;

use App\Material;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProTextMaterialDetailsTest extends DuskTestCase
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

        $material = Material::find(1);
        $material->type = 'Texto';
        $material->body = 'É um facto estabelecido de que um leitor é distraído pelo conteúdo legível de uma página quando analisa a sua mancha gráfica. Logo, o uso de Lorem Ipsum leva a uma distribuição mais ou menos normal de letras, ao contrário do uso de "Conteúdo aqui, conteúdo aqui", tornando-o texto legível. Muitas ferramentas de publicação electrónica e editores de páginas web usam actualmente o Lorem Ipsum como o modelo de texto usado por omissão, e uma pesquisa por "lorem ipsum" irá encontrar muitos websites ainda na sua infância. Várias versões têm evoluído ao longo dos anos, por vezes por acidente, por vezes propositadamente (como no caso do humor).';

        $this->browse(function (Browser $browser) use ($material) {
            $browser->clickLink('Recursos')
                    ->assertSee('Materiais')
                    ->clickLink('Materiais')
                    ->assertPathIs('/caregivers/public/materials')
                    ->assertSeeIn('select[name=\'materialType\'] option:first-child','Todos')
                    ->click('select[name=\'materialType\'] option:nth-child(2)','Texto')
                    ->press('Procurar')
                    ->assertPathIs('/caregivers/public/materials')
                    ->assertSeeIn('table tr:first-child td:nth-child(2)', 'Texto')
                    ->assertSeeIn('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->click('table tr:first-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertPathIs('/caregivers/public/materials/'.$material->id)
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
