<?php

namespace Tests\Browser;

use App\HealthcarePro;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HealthcareProExportCsvFileTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(HealthcarePro::find(14))
                    ->visit('/')
                    ->assertSeeIn('button.btn-primary', 'Exportar Acessos para o Formato CSV')
                    ->press('Exportar Acessos para o Formato CSV')
                    ->assertPathIs('/')
                    ->pause(5000);
        });
    }
}
