<?php

namespace Tests\Browser;

use App\Caregiver;
use App\Material;
use Tests\Browser\SuccessfullyLoginTest;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProDashboardCaregiverPatientsNeedsMaterialsTest extends DuskTestCase
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

        $caregiver = Caregiver::find(15);
        $patient = $caregiver->patients->get(0);
        $need = $patient->needs->get(0);
        $material = $need->materials->get(0);

        //$this->changeTypeFormat($material);

        $this->browse(function (Browser $browser) use ($patient, $need, $material){
            $browser->visit('/caregivers/public/caregivers/15/patients')
                    ->assertSeeIn('table tr:first-child td:first-child', $patient->name)
                    ->assertSeeIn('table tr:first-child td:last-child div div:nth-child(2) a', 'Necessidades')
                    ->click('table tr:first-child td:last-child div div:nth-child(2) a', 'Necessidades')
                    ->assertPathIs('/caregivers/public/patients/'.$patient->id.'/needs')
                    ->assertSeeIn('table.patient-needs tr:first-child td:first-child', $need->description)
                    ->assertSeeIn('table.patient-needs tr:first-child td:last-child div div:nth-child(2) a', 'Materiais')
                    ->click('table.patient-needs tr:first-child td:last-child div div:nth-child(2) a', 'Materiais')
                    ->assertPathIs('/caregivers/public/needs/'.$need->id.'/materials')
                    ->assertSeeIn('table tr:first-child td:first-child', $material->name)
                    ->pause(2000);
                    

            /*if($material->type == 'Texto') {
                $browser->assertSee('Texto: "'.(string)$material->body.'"');
            } else if($material->type == 'Imagem') {
                $browser->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                        ->assertVisible('img[alt=\''.$material->name.'\']');
            } else if($material->type == 'Video') {
                $browser->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                        ->assertVisible('video');
            } else if($material->type == 'Anexo' && $material->path) {
                $browser->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                        ->assertVisible('a[href=\'http://192.168.99.100/caregivers/public/material/'.$material->id.'/showContent\']');
            } else if ($material->type == 'Anexo' && !$material->path) {
                $browser->assertSeeIn('h4:nth-child(3)', 'Ficheiro:')
                        ->assertVisible('a[href=\''.$material->url.'\']');   
            } else if ($material->type == 'Contacto de Emergência') {
                $browser->assertSeeIn('h4:nth-child(3)', 'Número: '.$material->number);
            }

            $browser->assertSeeIn('h4:nth-child(4)', 'Criador: '.$material->creator->username)
                    ->assertSeeIn('h4:nth-child(5)', 'Data da criação: '.(string)$material->created_at)
                    ->assertSeeIn('h4:last-child', 'Data da última atualização: '.(string)$material->updated_at)
                    ->pause(2000);

    
      */  });
    }


    /*private function changeTypeFormat($material)
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
    }*/
}
