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
        //Users ids: 1 - 12
        factory(App\Caregiver::class, 5)->create();
        factory(App\Admin::class, 2)->create();
        factory(App\HealthcarePro::class, 5)->create();

        $hp_care = [
            [8, 1], [8, 2], [8, 5],
            [9, 2], [9, 3], [9, 4],
            [10, 1], [10, 5],
            [11, 3], [11, 4] 
        ]; 

        for($i = 0; $i < count($hp_care); $i++) {
            DB::table('caregiver_healthcare_pro')->insert([
                'healthcare_pro_id' => $hp_care[$i][0],
                'caregiver_id' => $hp_care[$i][1],
            ]);
        }

        factory(App\Patient::class, 10)->create();
        factory(App\Need::class, 5)->create();
        
        $n_p = [
            [1, 1], [1, 3], [1, 6],
            [2, 1], [2, 2], [2, 10],
            [3, 2], [3, 5], [3, 8],
            [4, 2], [4, 5], [4, 9],
            [5, 1], [5, 4], [5, 7]
        ]; 

        for($i = 0; $i < count($n_p); $i++) {
            DB::table('need_patient')->insert([
                'need_id' => $n_p[$i][0],
                'patient_id' => $n_p[$i][1],
            ]);
        }

        //Materials ids: 1 - 20
        factory(App\TextFile::class, 5)->create();
        factory(App\Image::class, 5)->create();
        factory(App\Video::class, 5)->create();
        factory(App\EmergencyContact::class, 5)->create();

        $n_m = [
            [1, 1], [1, 20], [1, 14],
            [2, 1], [2, 18], [2, 10],
            [3, 2], [3, 8],
            [4, 8], [4, 18],
            [5, 10], [5, 12], [5, 17]
        ]; 
      
        for($i = 0; $i < count($n_m); $i++) {
            DB::table('material_need')->insert([
                'need_id' => $n_m[$i][0],
                'material_id' => $n_m[$i][1],
            ]);
       
        }
        
       factory(App\Proceeding::class, 10)->create();
    }
    
}
