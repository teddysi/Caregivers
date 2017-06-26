<?php

use Illuminate\Database\Seeder;

class DuskTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		$this->buildCustomUsers();

		$hp_care = [
            [14, 15] 
        ]; 

        for($i = 0; $i < count($hp_care); $i++) {
            DB::table('caregiver_healthcare_pro')->insert([
                'healthcare_pro_id' => $hp_care[$i][0],
                'caregiver_id' => $hp_care[$i][1],
            ]);
        }

        factory(App\Patient::class, 1)->create();
        factory(App\Need::class, 1)->create();
        
        $n_p = [
            [1, 1]
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
            [1, 1], [1, 20], [1, 14]
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

    }


    private function buildCustomUsers()
    {
        //Admin
        $admin = new App\Admin();
        $admin->id = 1;
        $admin->username = 'admin';
        $admin->name = 'Admin';
        $admin->email = 'admin@mail.com';
        $admin->password = bcrypt('adminpw');
        $admin->remember_token = str_random(10);
        $admin->save();

        //HealthcarePro
        $healthcarePro = new App\HealthcarePro();
        $healthcarePro->id = 14
        $healthcarePro->username = 'healthcarePro';
        $healthcarePro->name = 'HealthcarePro';
        $healthcarePro->email = 'healthcarePro@mail.com';
        $healthcarePro->facility = 'Hospital de Leiria';
        $healthcarePro->job = 'Médico';
        $healthcarePro->password = bcrypt('propw');
        $healthcarePro->remember_token = str_random(10);
        $healthcarePro->save();

        //Caregiver
        $caregiver = new App\Caregiver();
        $caregiver->id = 15;
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
            $image->url = 'http://35.184.244.41/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
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
            $video->url = 'http://35.184.244.41/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
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
                $annex->url = 'http://35.184.244.41/caregivers/public/materialsAPI/'.($countMaterials+1).'/showContent';
                $annex->path = 'annexs/'.$name.'.pdf';
                $annex->mime = '.pdf';
            } else {
                $annex->url = 'https://www.youtube.com/watch?v=RoXmMD1rVP0';
            }
            $annex->created_by = $healthcare_pros->random()->id;
            $annex->save();
        }
    }
}
