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
        //Users ids: 1 - 15
        factory(App\Admin::class, 2)->create();
        factory(App\HealthcarePro::class, 5)->create();
        factory(App\Caregiver::class, 5)->create();
        $this->buildCustomUsers();

        $hp_care = [
            [3, 8], [3, 9], [3, 12],
            [4, 9], [4, 10], [4, 11],
            [5, 8], [5, 12],
            [6, 10], [6, 11] 
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

        //Materials ids: 1 - 23
        factory(App\TextFile::class, 5)->create();
        factory(App\Image::class, 5)->create();
        factory(App\Video::class, 5)->create();
        factory(App\EmergencyContact::class, 5)->create();
        factory(App\Composite::class, 3)->create();

        $cm_m = [
            [21, 1, 1], [21, 20, 2], [21, 14, 3],
            [22, 1, 1], [22, 18, 2], [22, 10, 3],
            [23, 1, 1], [23, 2, 2], [23, 8, 3], [23, 10, 4]
        ]; 
      
        for($i = 0; $i < count($cm_m); $i++) {
            DB::table('composite_material')->insert([
                'composite_id' => $cm_m[$i][0],
                'material_id' => $cm_m[$i][1],
                'order' => $cm_m[$i][2],
            ]);
        }

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

        $caregivers = App\Caregiver::all();

        foreach ($caregivers as $c) {
            $patients = $c->patients; 
                
            foreach ($patients as $p) {
                $needs = $p->needs;

                foreach ($needs as $n) {
                    for($i = 0; $i < count($n_m); $i++) {
                        if ($n->id == $n_m[$i][0]) {
                            $c_m = DB::table('caregiver_material')->where([
                                ['caregiver_id', $c->id],
                                ['material_id', $n_m[$i][1]],
                            ])->get();

                            if (count($c_m) == 0) {
                                DB::table('caregiver_material')->insert([
                                    'caregiver_id' => $c->id,
                                    'material_id' => $n_m[$i][1],
                                ]);
                            }
                        }
                    }
                }
            }
        }
        
       factory(App\Proceeding::class, 10)->create();

       factory(App\Log::class, 20)->create();
    }

    private function buildCustomUsers()
    {
        //Admin
        $admin = new App\Admin();
        $admin->username = 'admin';
        $admin->name = 'Admin';
        $admin->email = 'admin@mail.com';
        $admin->password = bcrypt('adminpw');
        $admin->remember_token = str_random(10);
        $admin->save();

        //HealthcarePro
        $healthcarePro = new App\HealthcarePro();
        $healthcarePro->username = 'healthcarePro';
        $healthcarePro->name = 'HealthcarePro';
        $healthcarePro->email = 'healthcarePro@mail.com';
        $healthcarePro->facility = 'Hospital de Leiria';
        $healthcarePro->job = 'Médico';
        $healthcarePro->password = bcrypt('propw');
        $healthcarePro->remember_token = str_random(10);
        $healthcarePro->save();

        //Caregiver
        $users = App\User::where('role', '<>', 'caregiver')->get();
        $caregiver = new App\Caregiver();
        $caregiver->username = 'caregiver';
        $caregiver->name = 'Caregiver';
        $caregiver->email = 'caregiver@mail.com';
        $caregiver->rate = 'Normal';
        $caregiver->location = 'Leiria';
        $caregiver->password = bcrypt('carepw');
        $caregiver->remember_token = str_random(10);
        $caregiver->created_by = $users->random()->id;
        $caregiver->save();
    }
    
}
