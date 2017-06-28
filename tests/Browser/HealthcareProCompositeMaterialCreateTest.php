<?php

namespace Tests\Browser;

use Tests\Browser\ResourcesDropDownTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Material;
use DB;

class HealthcareProCompositeMaterialCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $resources = new ResourcesDropDownTest();
        $resources->testBasicExample();

        $materials_count = count(Material::all());

        $new_material = [
            'Test Material Composite', //name
            'This is a test' //description
        ];
        

        $this->browse(function (Browser $browser) use ($new_material, $materials_count){
            $browser->clickLink('Materiais')
                    ->assertPathIs('/caregivers/public/materials')
                    ->assertSeeIn('div.second-options div:last-child a', 'Novo Composto')
                    ->clickLink('Novo Composto')
                    ->assertPathIs('/caregivers/public/materials/create/composite')
                    ->type('name', $new_material[0])
                    ->type('description', $new_material[1])
                    ->press('Adicionar Materiais')
                    ->pause(2000);

            $materials_count += 1;

            $browser->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials');

            $composite = Material::find($materials_count);

            if($composite->name !== $new_material[0]) {
                $this->assertTrue(false);
            }

            for($i = 1; $i < 4; $i++) {
                $browser->assertSeeIn('.materials-to-associate tr:first-child td:last-child button', 'Adicionar')
                        ->click('.materials-to-associate tr:first-child td:last-child button', 'Adicionar')
                        ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials');
                if($i === 1) {
                    $browser->assertSeeIn('.materials-associated tr:first-child td:last-child button', 'Remover');
                } else {
                    $browser->assertSeeIn('.materials-associated tr:nth-child('.$i.') td:last-child div.col-lg-4:last-child button', 'Remover');
                }
            }

            if(count($composite->materials) !== 3) {
                    $this->assertTrue(false);
                }

            $browser->assertSeeIn('.materials-associated tr:first-child td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:last-child div.col-lg-4:first-child button', 'Cima')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertSeeIn('.materials-associated tr:last-child td:last-child div.col-lg-4:first-child button', 'Cima');

            $mat1 = $composite->materials->get(0);
            $mat2 = $composite->materials->get(1);
            $mat3 = $composite->materials->get(2);

            $browser->assertSeeIn('.materials-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.materials-associated tr:first-child td:nth-child(2)', $mat1->name)
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:nth-child(2)', $mat2->name)
                    ->assertSeeIn('.materials-associated tr:last-child td:first-child', '3')
                    ->assertSeeIn('.materials-associated tr:last-child td:nth-child(2)', $mat3->name)
                    ->click('.materials-associated tr:first-child td:last-child div.col-lg-4:nth-child(2) button', 'Baixo')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('.materials-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.materials-associated tr:first-child td:nth-child(2)', $mat2->name)
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:nth-child(2)', $mat1->name)
                    ->click('.materials-associated tr:last-child td:last-child div.col-lg-4:first-child button', 'Cima')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:nth-child(2)', $mat3->name)
                    ->assertSeeIn('.materials-associated tr:last-child td:first-child', '3')
                    ->assertSeeIn('.materials-associated tr:last-child td:nth-child(2)', $mat1->name);

            $orderMat1 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat1->id]])->first()->order;

            $orderMat2 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat2->id]])->first()->order;

            $orderMat3 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat3->id]])->first()->order;

            if($orderMat1 != 3 || $orderMat2 != 1 || $orderMat3 != 2) {
                $this->assertTrue(false);
            }

            $browser->click('.materials-associated tr:nth-child(2) td:last-child div.col-lg-4:last-child button', 'Remover')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('.materials-associated tr:first-child td:first-child', '1')
                    ->assertSeeIn('.materials-associated tr:first-child td:nth-child(2)', $mat2->name)
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:first-child', '2')
                    ->assertSeeIn('.materials-associated tr:nth-child(2) td:nth-child(2)', $mat1->name)
                    ->assertSeeIn('.materials-to-associate tr:first-child td:first-child', $mat3->name)
                    ->assertSeeIn('.materials-to-associate tr:first-child td:last-child button', 'Adicionar');

            $composite = Material::find($materials_count);

            if(count($composite->materials) !== 2) {
                    $this->assertTrue(false);
                }

            $mat1 = $composite->materials->get(0);
            $mat2 = $composite->materials->get(1);

            $orderMat1 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat1->id]])->first()->order;

            $orderMat2 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat2->id]])->first()->order;

            if($orderMat1 != 2 || $orderMat2 != 1) {
                $this->assertTrue(false);
            }

            $browser->pause(2000)
                    ->clickLink('Concluído')
                    ->assertPathIs('/caregivers/public/materials')
                    ->pause(2000);
        });
    }
}