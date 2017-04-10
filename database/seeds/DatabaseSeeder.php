<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        factory(App\Caregiver::class, 5)->create()->each(function ($c){
            $c->patients()->save(factory(App\Patient::class, 2)->make());
        });
        factory(App\Patient::class, 10)->create();
        factory(App\Admin::class, 2)->create();
        factory(App\HealthcarePro::class, 5)->create();
        

        $hp_care = [
            [8, 1],
            [8, 2],
            [9, 2],
            [9, 3],
            [9, 4],
            [10, 1],
            [10, 5],
            [11, 3],
            [11, 4],
            [8, 5],
        ]; 

        for($i = 0; $i < count($hp_care); $i++) {
            DB::table('healthcarepro_caregiver')->insert([
                'healthcarepro_id' => $hp_care[$i][0],
                'caregiver_id' => $hp_care[$i][1],
            ]);
        }
        
    }
    
}
