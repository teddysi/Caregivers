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
            [6, 10], [6, 11],
            [14, 15] 
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
        
        factory(App\Access::class, 10)->create();

        $this->buildQuestions();
        $this->buildQuizs();

        $qz_q = [
            [1, 1, 1], [1, 2, 2], 
            [2, 3, 1], [2, 4, 2], 
            [3, 5, 1], [3, 6, 2]
        ]; 
      
        for($i = 0; $i < count($qz_q); $i++) {
            DB::table('quiz_question')->insert([
                'quiz_id' => $qz_q[$i][0],
                'question_id' => $qz_q[$i][1],
                'order' => $qz_q[$i][2],
            ]);
        }

        $this->buildEvaluations();
        factory(App\Log::class, 50)->create();
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
            $image->url = 'http://35.184.244.41/materialsAPI/'.($countMaterials+1).'/showContent';
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
            $video->url = 'http://35.184.244.41/materialsAPI/'.($countMaterials+1).'/showContent';
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
                $annex->url = 'http://35.184.244.41/materialsAPI/'.($countMaterials+1).'/showContent';
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
        $evaluationsName = [
            'Evaluation-1', 'Evaluation-2', 'Evaluation-3', 'Evaluation-4', 'Evaluation-5'
        ];
      
        foreach ($evaluationsName as $name) {
            $evaluation = new App\Evaluation();
            $evaluation->type = 'Pela aplicação';
            $evaluation->description = $name;
            $evaluation->model = 'Model X';
            $evaluation->path = 'evaluations/'.($i+1).'.pdf';
            $evaluation->mime = '.pdf';
            
            if($i < 2) {
                $healthcare_pro;
                do {
                    $healthcare_pro = App\HealthcarePro::all()->random();
                } while (count($healthcare_pro->caregivers) == 0);
                
                $caregiver = $healthcare_pro->caregivers->random();
                $evaluation->created_by = $healthcare_pro->id;
                $evaluation->caregiver_id = $caregiver->id;
            } elseif ($i == 3) {
                $evaluation->created_by = 14;
                $evaluation->caregiver_id = 15;
            } elseif ($i == 4) {
                $evaluation->created_by = 14;
                $evaluation->patient_id = 10;
            } else {
                $evaluation->created_by = App\HealthcarePro::all()->random()->id;
                $evaluation->patient_id = $patients->random()->id;
            }
            $evaluation->save();
            $i++;
        }
        $evaluationsQuizs = ['Evaluation-quiz-1', 'Evaluation-quiz-2', 'Evaluation-quiz-3'];

        $evaluationC = new App\Evaluation();
        $evaluationC->type = 'Pela aplicação';
        $evaluationC->description = $evaluationsQuizs[0];
        $evaluationC->model = 'Model X';
        $evaluationC->created_by = 14;
        $evaluationC->caregiver_id = 15;
        $evaluationC->answered_by = 15;
        $evaluationC->save();

        $qz_c = [
            [1, 15, 6]
        ];

        for($i = 0; $i < count($qz_c); $i++) {
            DB::table('quiz_caregiver')->insert([
                'quiz_id' => $qz_c[$i][0],
                'caregiver_id' => $qz_c[$i][1],
                'evaluation_id' => $qz_c[$i][2],
            ]);
        }

        $evaluationP = new App\Evaluation();
        $evaluationP->type = 'Pela aplicação';
        $evaluationP->description = $evaluationsQuizs[1];
        $evaluationP->model = 'Model X';
        $evaluationP->created_by = 14;
        $evaluationP->patient_id = 10;
        $evaluationP->answered_by = 15;
        $evaluationP->save();

        $qz_p = [
            [1, 10, 7]
        ];

        for($i = 0; $i < count($qz_p); $i++) {
            DB::table('quiz_patient')->insert([
                'quiz_id' => $qz_p[$i][0],
                'patient_id' => $qz_p[$i][1],
                'evaluation_id' => $qz_p[$i][2],
            ]);
        }

        $evaluationM = new App\Evaluation();
        $evaluationM->type = 'Pela aplicação';
        $evaluationM->description = $evaluationsQuizs[2];
        $evaluationM->model = 'Model X';
        $evaluationM->created_by = 14;
        $evaluationM->material_id = 1;
        $evaluationM->answered_by = 15;
        $evaluationM->save();

        $qz_m = [
            [1, 1, 15, 8]
        ];

        for($i = 0; $i < count($qz_m); $i++) {
            DB::table('quiz_material')->insert([
                'quiz_id' => $qz_m[$i][0],
                'material_id' => $qz_m[$i][1],
                'caregiver_id' => $qz_m[$i][2],
                'evaluation_id' => $qz_m[$i][3],
            ]);
        }
    }

    private function buildQuestions()
    {
        $healthcare_pros = App\HealthcarePro::all();
        $questions_text = [
            'Como está?', 'Tem comido?', 'Que horas são?', 'Choveu ontem?', 'Está com dores?', 
            'Amanhã chove?'
        ];
        $question_type = [
            'text', 'radio'
        ];

        $question_values_radio = [
            'Sim;Não;', '1;2;3;4;5;', 'Consigo realizar;Não consigo realizar;'
        ];

        /*$question_values_multiple_choice = [
            'Sei realizar;Não sei realizar;Percebi, mas não consigo realizar;Não percebi;O material não é esclarecedor;Não é possível compreender o material;O material é fácil de perceber'
        ];*/
        $i = 0;
        $index = 1;
        foreach ($questions_text as $question_text) {

            $question = new App\Question();
            $question->question = $question_text;
            $question->created_by = $healthcare_pros->random()->id;
            if($i >= 3) {
                $index = 1;
            } else {
                $index = 0;
            }

            $question->type = $question_type[$index];

            if($question->type == 'radio') {       
                $index = array_rand($question_values_radio,1);
                $question->values = $question_values_radio[$index];
            }

            $question->save();
            $i++;
        }
    }

    public function buildQuizs()
    {
        $names = [
            'Quiz-1', 'Quiz-2', 'Quiz-3'
        ];
        $healthcare_pros = App\HealthcarePro::all();

        foreach ($names as $name) {
            $quiz = new App\Quiz();
            $quiz->name = $name;
            $quiz->blocked = false;
            $quiz->created_by = $healthcare_pros->random()->id;

            $quiz->save();
        }

    }
}
