<?php

namespace Tests\Browser;

use Tests\Browser\ResourcesDropDownTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Material;
use DB;

class SuccessfullyCreateCompositeMaterialTest extends DuskTestCase
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

        $new_material = [
            'Test Material Composite', //name
            'This is a test' //description
        ];
        

        $this->browse(function (Browser $browser) use ($new_material){
            $browser->click('a.nav_materials')
                    ->assertPathIs('/caregivers/public/materials')
                    ->click('a.create_composite_material_button')
                    ->assertPathIs('/caregivers/public/materials/create/composite')
                    ->type('name', $new_material[0])
                    ->type('description', $new_material[1])
                    ->press('Adicionar Materiais');

            $materials_count = count(Material::all());
            $composite = Material::find($materials_count);

            if($composite->name !== 'Test Material Composite') {
                $browser->assertSee('Error Creating Composite Material. DB not updated!!');
            }

            $browser->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials');

            $browser->whenAvailable('.materiais-por-associar table tr:first-child button', function($bt) {
                $bt->click
            })
            for($i = 1; $i < 4; $i++) {
                $material = Material::find($i);

                $browser->assertSee($material->name)
                        ->click('button.add_composite_'.$material->id.'_button')
                        ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                        ->assertSeeIn('td.composite_'.$material->id.'_order', $i)
                        ->assertSeeIn('td.composite_'.$material->id.'_name', $material->name)
                        ->assertSeeIn('button.remove_composite_'.$material->id.'_button', 'Remover');
            }

            if(count($composite->materials) !== 3) {
                    $browser->assertSee('Error Assoating materials to Composite. DB not updated!!');
                }

            foreach($composite->materials as $material) {
                if($material->id === 1) {
                    $browser->assertSeeIn('button.down_composite_'.$material->id.'_button', 'Baixo');    
                } else if($material->id === count($composite->materials)) {
                    $browser->assertSeeIn('button.up_composite_'.$material->id.'_button', 'Cima');
                } else {
                    $browser->assertSeeIn('button.down_composite_'.$material->id.'_button', 'Baixo')
                            ->assertSeeIn('button.up_composite_'.$material->id.'_button', 'Cima');
                }
                
            }

            $mat1 = $composite->materials->get(0);
            $mat2 = $composite->materials->get(1);
            $mat3 = $composite->materials->get(2);

            $browser->click('button.down_composite_'.$mat1->id.'_button')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('td.composite_'.$mat1->id.'_order', '2')
                    ->assertSeeIn('td.composite_'.$mat2->id.'_order', '1')
                    ->click('button.up_composite_'.$mat3->id.'_button')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('td.composite_'.$mat3->id.'_order', '2')
                    ->assertSeeIn('td.composite_'.$mat1->id.'_order', '3');

            $orderMat1 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat1->id]])->first()->order;

            $orderMat2 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat2->id]])->first()->order;

            $orderMat3 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat3->id]])->first()->order;

            if($orderMat1 !== 3 || $orderMat2 !== 1 || $orderMat3 !==2) {
                $browser->assertSee('Error updating materials order in DB!!!!');
            }

            $browser->click('button.remove_composite_'.$mat3->id.'_button')
                    ->assertPathIs('/caregivers/public/materials/'.$materials_count.'/materials')
                    ->assertSeeIn('td.composite_'.$mat1->id.'_order', '2')
                    ->assertSeeIn('td.composite_'.$mat2->id.'_order', '1')
                    ->assertSeeIn('button.add_composite_'.$mat3->id.'_button', 'Adicionar');

            $composite = Material::find($materials_count);

            if(count($composite->materials) !== 2) {
                    $browser->assertSee('Error Deleting material from Composite. DB not updated!!');
                }

            $mat1 = $composite->materials->get(0);
            $mat2 = $composite->materials->get(1);

            $orderMat1 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat1->id]])->first()->order;

            $orderMat2 = $orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $mat2->id]])->first()->order;

            if($orderMat1 !== 2 || $orderMat2 !== 1) {
                $browser->assertSee('Error updating materials order after deleting in DB!!!!');
            }

            $browser->pause(2000)
                    ->assertSeeIn('button.down_composite_'.$mat2->id.'_button', 'Baixo')
                    ->assertSeeIn('button.up_composite_'.$mat1->id.'_button', 'Cima')
                    ->click('a.composite_conclude_button')
                    ->assertPathIs('/caregivers/public/materials')
                    ->pause(2000);
        });
    }
}
