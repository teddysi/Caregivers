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

        factory(App\Patient::class, 9)->create();
        factory(App\Patient::class, 1)->create();
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

        //Materials ids: 1 - 28
        factory(App\Text::class, 5)->create();
        $this->buildImages();
        $this->buildVideos();
        factory(App\EmergencyContact::class, 5)->create();
        $this->buildAnnexs();
        factory(App\Composite::class, 3)->create();

        $cm_m = [
            [26, 1, 1], [26, 20, 2], [26, 14, 3],
            [27, 1, 1], [27, 18, 2], [27, 10, 3],
            [28, 1, 1], [28, 2, 2], [28, 8, 3], [28, 10, 4]
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
            [2, 1], [2, 18], [2, 26],
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

       $this->buildEvaluations();

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

    private function buildImages()
    {
        $countMaterials = count(App\Material::all());
        $healthcare_pros = App\HealthcarePro::all();
        $images_name = [
            'Imagem-1', 'Imagem-2', 'Imagem-3', 'Imagem-4', 'Imagem-5'
        ]; 
      
        foreach ($images_name as $name) {
            $image = new App\Image();
            $image->name = $name;
            $image->description = $name.' description';
            $image->type = 'image';
            $image->url = 'http://192.168.99.100/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
            $image->path = 'images/'.$name.'.jpg';
            $image->mime = '.jpg';
            $image->created_by = $healthcare_pros->random()->id;
            $image->save();
        }
    }
    
    private function buildVideos()
    {
        $countMaterials = count(App\Material::all());
        $healthcare_pros = App\HealthcarePro::all();
        $videos_name = [
            'Video-1', 'Video-2', 'Video-3', 'Video-4', 'Video-5'
        ]; 
      
        foreach ($videos_name as $name) {
            $video = new App\Video();
            $video->name = $name;
            $video->description = $name.' description';
            $video->type = 'video';
            $video->url = 'http://192.168.99.100/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
            $video->path = 'videos/'.$name.'.mp4';
            $video->mime = '.mp4';
            $video->created_by = $healthcare_pros->random()->id;
            $video->save();
        }
    }

    private function buildAnnexs()
    {
        $countMaterials = count(App\Material::all());
        $healthcare_pros = App\HealthcarePro::all();
        $annexs_name = [
            'Anexo-1', 'Anexo-2', 'Anexo-3', 'Anexo-4', 'Anexo-5'
        ]; 
      
        foreach ($annexs_name as $name) {
            $annex = new App\Annex();
            $annex->name = $name;
            $annex->description = $name.' description';
            $annex->type = 'annex';
            if ($annex->name != 'Anexo-4' && $annex->name != 'Anexo-5') {
                $annex->url = 'http://192.168.99.100/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
                $annex->path = 'annexs/'.$name.'.pdf';
                $annex->mime = '.pdf';
            } else {
                $annex->url = 'https://www.youtube.com/watch?v=RoXmMD1rVP0';
            }
            $annex->created_by = $healthcare_pros->random()->id;
            $annex->save();
        }
    }

    private function buildEvaluations()
    {
        $patients = App\Patient::all();
        $i = 0;
        $evaluations_name = [
            'Evaluation-1', 'Evaluation-2', 'Evaluation-3', 'Evaluation-4', 'Evaluation-5'
        ]; 
      
        foreach ($evaluations_name as $name) {
            $healthcare_pro;
            do {
                $healthcare_pro = App\HealthcarePro::all()->random();
            } while (count($healthcare_pro->caregivers) == 0);
            
            $caregiver = $healthcare_pro->caregivers->random();
            $evaluation = new App\Evaluation();
            $evaluation->name = $name;
            $evaluation->description = $name.' description';
            $evaluation->path = 'evaluations/'.$name.'.pdf';
            $evaluation->mime = '.pdf';
            $evaluation->created_by = $healthcare_pro->id;
            if($i < 2) {
                $evaluation->caregiver_id = $caregiver->id;
                $i++;
            } else {
                $evaluation->patient_id = $patients->random()->id;
            }


            $evaluation->save();
        }
    }
}
