<?php

namespace Tests\Browser;

use App\Caregiver;
use App\HealthcarePro;
use App\Material;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProCaregiverMaterialsAssociateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group healthcarepro
     */
    public function testBasicExample()
    {

        $this->browse(function (Browser $browser) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSee('Caregiver')
                    ->assertSeeIn('table tr:first-child td:first-child', 'Caregiver')
                    ->assertSeeIn('table tr:first-child td:last-child div:nth-child(3) a', 'Materiais')
                    ->click('table tr:first-child td:last-child div:nth-child(3) a', 'Materiais')
                    ->assertPathIs('/caregivers/15/materials')
                    ->assertSee('Associar Materiais')
                    ->assertVisible('button.btn-default')
                    ->assertSeeIn('button.btn-default', 'Associar')
                    ->assertSeeIn('label[for=\'need\']', 'Necessidade')
                    ->assertVisible('select[name=\'need\']');

            $caregiver = Caregiver::find(15);
            $patients = $caregiver->patients;
            $patientsNeeds = [];
            
            foreach ($patients as $patient) {
                foreach ($patient->needs as $need) {
                    $needAlreadyExists = false;
                    foreach ($patientsNeeds as $patientsNeed) {
                        if ($need->id == $patientsNeed->id) {
                            $needAlreadyExists = true;
                        }
                    }

                    if (!$needAlreadyExists) {
                        array_push($patientsNeeds, $need);
                    }
                }
            }
            usort($patientsNeeds, array($this, 'cmp'));

            for ($i = 0, $j = 1; $i < count($patientsNeeds); $i++, $j++) {
                if($i == 0) {
                    $browser->assertSeeIn('select[name=\'need\'] option:first-child', $patientsNeeds[$i]->description);
                } else if ($i == count($patientsNeed) && count($patientsNeed) > 1) {
                    $browser->assertSeeIn('select[name=\'need\'] option:last-child', $patientsNeeds[$i]->description);
                } else {
                    $browser->assertSeeIn('select[name=\'need\'] option:nth-child('.$j.')', $patientsNeeds[$i]->description);
                }
            }

            $browser->assertSeeIn('label[for=\'material\']', 'Material')
                    ->assertVisible('select[name=\'material\']');
            $materials = Material::all();
            for ($i = 0, $j = 1; $i < count($materials); $i++, $j++) {
                if($i == 0) {
                    $browser->assertSeeIn('select[name=\'material\'] option:first-child', $materials[$i]->name);
                } else if ($i == count($materials) && count($materials) > 1) {
                    $browser->assertSeeIn('select[name=\'material\'] option:last-child', $materials[$i]->name);
                } else {
                    $browser->assertSeeIn('select[name=\'material\'] option:nth-child('.$j.')', $materials[$i]->name);
                }
            }
            
            $browser->assertSee('Necessidades dos Utentes de Caregiver')     
                    ->assertVisible('table.patients-needs')
                    ->assertSeeIn('table th:first-child', 'Descrição')
                    ->assertSeeIn('table th:nth-child(2)', 'Criador')
                    ->assertSeeIn('table th:last-child', 'Ações');
            $i = 1;
            foreach ($patientsNeeds as $patientsNeed) {
                if( $i == 1) {
                    $browser->assertSeeIn('table.patients-needs tr:first-child td:first-child', $patientsNeed->description)
                            ->assertSeeIn('table.patients-needs tr:first-child td:nth-child(2)', $patientsNeed->creator->username)
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:nth-child(2) a', 'Materiais')
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:nth-child(3) a', 'Editar');
                } else if( $i == count($patientsNeeds) && count($patientsNeeds) > 1) {
                    $browser->assertSeeIn('table.patients-needs tr:last-child td:first-child', $patientsNeed->description)
                            ->assertSeeIn('table.patients-needs tr:last-child td:nth-child(2)', $patientsNeed->creator->username)
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:nth-child(2) a', 'Materiais')
                            ->assertSeeIn('table.patients-needs tr:first-child td:last-child div div:nth-child(3) a', 'Editar');
                } else {
                    $browser->assertSeeIn('table.patients-needs tr:nth-child('.$i.') td:first-child', $patientsNeed->description)
                            ->assertSeeIn('table.patients-needs tr:nth-child('.$i.') td:nth-child(2)', $patientsNeed->creator->username)
                            ->assertSeeIn('table.patients-needs tr:nth-child('.$i.') td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.patients-needs tr:nth-child('.$i.') td:last-child div div:nth-child(2) a', 'Materiais')
                            ->assertSeeIn('table.patients-needs tr:nth-child('.$i.') td:last-child div div:nth-child(3) a', 'Editar');
                }
                $i++;
            }

            $browser->assertSee('Materiais de Caregiver')     
                    ->assertVisible('table.caregiver-materials')
                    ->assertSeeIn('table.caregiver-materials th:first-child', 'Nome')
                    ->assertSeeIn('table.caregiver-materials th:nth-child(2)', 'Tipo')
                    ->assertSeeIn('table.caregiver-materials th:nth-child(3)', 'Criador')
                    ->assertSeeIn('table.caregiver-materials th:last-child', 'Ações');
            $i = 1;
            $materials = $caregiver->materials;

            foreach ($materials as $material) {
                $this->changeTypeFormat($material);
            }

            foreach ($materials as $material) {
                if( $i == 1) {
                    $browser->assertSeeIn('table.caregiver-materials tr:first-child td:first-child', $material->name)
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:nth-child(2)', $material->type)
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:nth-child(3)', $material->creator->username)
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:last-child div div:nth-child(2) a', 'Editar')
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:last-child div div:nth-child(3) a', 'Avaliações')
                            ->assertSeeIn('table.caregiver-materials tr:first-child td:last-child div div:last-child button', 'Desassociar');
                } else if( $i == count($materials) && count($materials) > 1) {
                    $browser->assertSeeIn('table.caregiver-materials tr:last-child td:first-child',        $material->name)
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:nth-child(2)', $material->type)
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:nth-child(3)', $material->creator->username)
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:nth-child(2) a', 'Editar')
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:nth-child(3) a', 'Avaliações')
                            ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:last-child button', 'Desassociar');
                } else {
                    $browser->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:first-child', $material->name)
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:nth-child(2)', $material->type)
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:nth-child(3)', $material->creator->username)
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:last-child div div:first-child a', 'Detalhes')
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:last-child div div:nth-child(2) a', 'Editar')
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:last-child div div:nth-child(3) a', 'Avaliações')
                            ->assertSeeIn('table.caregiver-materials tr:nth-child('.$i.') td:last-child div div:last-child button', 'Desassociar');
                }
                $i++;
            }

            $browser->assertSeeIn('a.btn-default', 'Voltar Atrás')
                    ->assertVisible('a.btn-default')
                    ->click('select[name=\'material\'] option:last-child', 'Composto 48')
                    ->press('Associar')
                    ->assertPathIs('/caregivers/15/materials');

            $materials_count = count(Material::all());
            $material_associated = Material::find($materials_count);

            $caregiver = Caregiver::find(15);
            $index_material_associated = count($caregiver->materials) - 1;
            $m = $caregiver->materials->get($index_material_associated);

            if($material_associated->name != $m->name) {
                $this->assertTrue(false);
            }
            
            $this->changeTypeFormat($material_associated);

            $browser->assertSeeIn('table.caregiver-materials tr:last-child td:first-child',        $material_associated->name)
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:nth-child(2)', $material_associated->type)
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:nth-child(3)', $material_associated->creator->username)
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:first-child a', 'Detalhes')
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:nth-child(2) a', 'Editar')
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:nth-child(3) a', 'Avaliações')
                    ->assertSeeIn('table.caregiver-materials tr:last-child td:last-child div div:last-child button', 'Desassociar');

        });
    }

    private function cmp($a, $b)
    {
        return $a->id > $b->id ? 1 : -1;
    }

    private function changeTypeFormat($material)
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
