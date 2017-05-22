<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Material;
use App\Text;
use App\Video;
use App\Image;
use App\EmergencyContact;
use App\Annex;
use App\Composite;
use App\User;
use App\Log;
use Storage;
use Response;
use DB;

class MaterialsController extends Controller
{
    private $messages = [
	    'name.unique' =>  'Esse nome já existe. Escolha outro.',
	    'name.required' => 'O nome tem que ser preenchido.',
	    'name.min' => 'O nome tem que ser maior que 4 letras.',
	    'description.required' => 'A descrição tem que ser preenchida.',
	    'description.min' => 'A descrição tem que ser maior que 4 letras.',
	    'body.required_if' => 'O campo texto não pode ser vazio.',
	    'pathImage.required_if' => 'Introduza uma image com um dos seguintes formatos: jpeg, png, jpg, gif, svg.',
	    'pathImage.mimes' => 'A imagem tem que estar num dos seguintes formatos: jpeg, png, jpg, gif, svg.',
	    'pathVideo.required_if' => 'Introduza um video em formato mp4.',
	    'pathVideo.mimes' => 'O video tem que ser em formato mp4.',
	    'number.required_if' => 'Introduza um número de contacto.',
	    'pathAnnex.required_if' => 'Introduza um anexo.',
	    'url.required_if' => 'Introduza um url.',
	    'url.url' => 'Introduza um url válido.',
	    'selectType.required_if' => 'Escolha um tipo de anexo.'
 	];

	public function index(Request $request)
	{
		$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['materialName' => '', 'materialType' => '', 'materialCreator' => '', 'materialSort' => '', 'materialPages' => '', 'materialBlocked' => ''];

        if ($request->has('dashboard')) {
            $this->saveDataFieldsToSession($request);
            $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
        } else {
            if ($this->isRequestDataEmpty($request)) {
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            } else {
                $this->saveDataFieldsToSession($request);
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            }
        }

		if (!empty($searchData['materialName'])) {
           	$where[] = ['name', 'like', '%'.$searchData['materialName'].'%'];
        }

        if (!empty($searchData['materialType'])) {
			if($searchData['materialType'] != 'all') {
                $where[] = ['type', 'like', '%'.$searchData['materialType'].'%'];
            }
        }

        if (!empty($searchData['materialCreator'])) {
			$user = User::where('username','like','%'.$searchData['materialCreator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['materialBlocked'])) {
            if($searchData['materialBlocked'] == 'just_blocked') {
                $where[] = ['blocked', 1];
            } elseif($searchData['materialBlocked'] == 'just_unblocked') {
                $where[] = ['blocked', 0];
            }
        }

		if (!empty($searchData['materialSort'])) {
            if($searchData['materialSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['materialSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['materialSort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['materialSort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            } elseif($searchData['materialSort'] == 'type_az') {
                $col = 'type';
                $order = 'asc';
            } elseif($searchData['materialSort'] == 'type_za') {
                $col = 'type';
                $order = 'desc';
            }
        }

		if (!empty($searchData['materialPages'])) {
            $pages = $searchData['materialPages'];
        }

		$materials = Material::where($where)->orderBy($col, $order)->paginate((int)$pages);
		foreach ($materials as $material) {
			$this->changeTypeFormat($material);
		}

		return view('materials.index', compact('materials','searchData'));
	}
	
    public function create($type)
	{	
		return view('materials.create', compact('type'));
	}

	public function store(Request $request)
	{
		$this->validate($request, [
				'name' => 'required|min:4|unique:materials',
				'description' => 'required|min:4',
				'body' => 'nullable|required_if:type,text',
				'pathImage' => 'nullable|required_if:type,image|mimes:jpeg,png,jpg,gif,svg',
				'pathVideo' => 'nullable|required_if:type,video|mimes:mp4',
				'pathAnnex' => 'nullable|required_if:selectType,2',
				'url' => 'nullable|url|required_if:selectType,0|required_if:selectType,1',
				'mime' => 'nullable',
				'number' => 'nullable|required_if:type,emergencyContact',
				'selectType' => 'required_if:type,annex',
		], $this->messages);

		$material;
		switch ($request->input('type')) {
			case 'text':
				$material = new Text();
				$material->body = $request->input('body');
				break;

			case 'image':
				$material = new Image();
				$originalName = $request->pathImage->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->url = $request->root() . '/materials/'.$material->id.'/showContent';
				$material->path = $request->file('pathImage')->storeAs('images', $request->input('name') . '.' . $whatIWant);
				$material->mime = '.' . $whatIWant;
				break;

			case 'video':
				$material = new Video();
				$originalName = $request->pathVideo->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->url = $request->root() . '/materials/'.$material->id.'/showContent';
				$material->path = $request->file('pathVideo')->storeAs('videos', $request->input('name') . '.' . $whatIWant);
				$material->mime = '.' . $whatIWant;
				break;

			case 'annex':
				$material = new Annex();
				if ($request->pathAnnex) {
					$originalName = $request->pathAnnex->getClientOriginalName();
					$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
					$material->url = $request->root() . '/materials/'.$material->id.'/showContent';
					$material->path = $request->file('pathAnnex')->storeAs('annexs', $request->input('name') . '.' . $whatIWant);
					$material->mime = '.' . $whatIWant;
				} else {
					$material->url = $request->input('url');
				}
				break;

			case 'emergencyContact':
				$material = new EmergencyContact();
				$material->number = $request->input('number');
				break;

			default:
				break;
		}
		
		$material->name = $request->input('name');
		$material->description = $request->input('description');
		$material->created_by = Auth::user()->id;
		$material->save();

		$log = new Log();
		$log->performed_task = 'Criou o Material: ' . $material->name;
		$log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
		$log->save();

		return redirect('/');
	}

	public function show(Material $material)
	{
		$this->changeTypeFormat($material);

		if ($material->type == 'Composto') {
			$compositeMaterials = $material->materials()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10);
			$compositeMaterials->setPageName('compositeMaterials');
			foreach ($compositeMaterials as $compositeMaterial) {
				$this->changeTypeFormat($compositeMaterial);
			}
		}

		$logs = $material->logs()->paginate(10, ['*'], 'logs');
		$logs->setPageName('logs');

		return view('materials.show', compact('material', 'compositeMaterials', 'logs'));
	}

	public function edit(Material $material) {
		$this->changeTypeFormat($material);
		return view('materials.edit', compact('material'));
	}

	public function update(Request $request, Material $material)
	{
		$this->validate($request, [
			'name' => 'required|min:4|unique:materials,name,'.$material->id,
			'description' => 'required|min:4',
			'body' => 'nullable|required_if:type,Texto',
			'pathImage' => 'nullable|required_if:type,Imagem|mimes:jpeg,png,jpg,gif,svg',
			'pathVideo' => 'nullable|required_if:type,Video|mimes:mp4',
			'pathAnnex' => 'nullable',
			'url' => 'nullable|url',
			'mime' => 'nullable',
			'number' => 'nullable|required_if:type,Contacto de Emergência',
		], $this->messages);

		$material->name = $request->input('name');
		$material->description = $request->input('description');
		switch ($material->type) {
			case 'text':
				$material->body = $request->input('body');
				break;

			case 'image':
				$originalName = $request->pathImage->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->path = $request->file('pathImage')->storeAs('images', $request->input('name') . '.' . $whatIWant);
				$material->mime = '.' . $whatIWant;
				break;

			case 'video':
				$originalName = $request->pathVideo->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->path = $request->file('pathVideo')->storeAs('videos', $request->input('name') . '.' . $whatIWant);
				$material->mime = '.' . $whatIWant;
				break;

			case 'annex':
				if ($request->pathAnnex) {
					$originalName = $request->pathAnnex->getClientOriginalName();
					$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
					$material->path = $request->file('pathAnnex')->storeAs('annexs', $request->input('name') . '.' . $whatIWant);
					$material->mime = '.' . $whatIWant;
				} else if ($material->path) {
					Storage::move($material->path, 'annexs/'. $request->input('name').$material->mime);
					$material->path = 'annexs/'. $request->input('name').$material->mime;
				}

				if ($request->input('url')) {
					$material->url = $request->input('url');
				}
				break;

			case 'emergencyContact':
				$material->number = $request->input('number');
				break;

			default:
				break;
		}
		$material->save();

		$log = new Log();
		$log->performed_task = 'Atualizou o Material: ' . $material->name;
		$log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
		$log->save();

		return redirect('/');
	}

	public function toggleBlock(Request $request, Material $material)
	{
		if ($material->blocked == 0) {
            $material->blocked = 1;
            $material->save();

			$log = new Log();
			$log->performed_task = 'Bloqueou o Material: ' . $material->name;
			$log->done_by = Auth::user()->id;
			$log->material_id = $material->id;
			$log->save();

			$this->changeTypeFormat($material);
            $request->session()->flash('blockedStatus', "$material->type $material->name foi bloqueado.");
        } elseif ($material->blocked == 1) {
            $material->blocked = 0;
            $material->save();

			$log = new Log();
			$log->performed_task = 'Desbloqueou o Material: ' . $material->name;
			$log->done_by = Auth::user()->id;
			$log->material_id = $material->id;
			$log->save();

			$this->changeTypeFormat($material);
            $request->session()->flash('blockedStatus', "$material->type $material->name foi desbloqueado.");
        }

        return back();
	}

	public function materials(Material $material)
	{
		$compositeMaterials = $material->materials()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'compositeMaterials');
		$compositeMaterials->setPageName('compositeMaterials');

		$notCompositeMaterials = Material::whereNotIn('id', $material->materials->modelKeys())
									->where('type', '<>', 'composite')
									->paginate(10, ['*'], 'notCompositeMaterials');
		$notCompositeMaterials->setPageName('notCompositeMaterials');

		return view('materials.materials', compact('material', 'compositeMaterials', 'notCompositeMaterials'));
	}

	public function addMaterials(Request $request)
	{
		$this->validate($request, [
				'name' => 'required|min:4|unique:materials',
				'description' => 'required|min:4',
		], $this->messages);

		$composite = new Composite();		
		$composite->name = $request->input('name');
		$composite->description = $request->input('description');
		$composite->created_by = Auth::user()->id;
		$composite->save();

		$log = new Log();
        $log->performed_task = 'Criou o Material Composto: ' . $composite->name;
        $log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
        $log->save();

		return redirect()->route('materials.materials', ['material' => $composite->id]); 
	}

	public function addMaterial(Material $composite, Material $material)
	{
		$count = count($composite->materials()->get());
		$composite->materials()->attach([$material->id => ['order'=> $count + 1]]);

		$log = new Log();
        $log->performed_task = 'Adicionou o Material: ' . $material->name. ' ao Material Composto: ' . $composite->name;
		$log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
        $log->save();

		$log = new Log();
        $log->performed_task = 'Adicionou o Material: ' . $material->name. ' ao Material Composto: ' . $composite->name;
		$log->done_by = Auth::user()->id;
		$log->material_id = $composite->id;
        $log->save();

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function removeMaterial(Material $composite, Material $material)
	{
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$composite->materials()->detach($material->id);
		$materialsToUpdateOrder = $composite->materials()->where('order', '>', $orderOfMaterial)->get();
		foreach ($materialsToUpdateOrder as $materialToUpdate) {
			$orderOfMaterialToUpdate = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $materialToUpdate->id]])->first()->order;
			$composite->materials()->updateExistingPivot($materialToUpdate->id, ['order' => $orderOfMaterialToUpdate - 1]);
		}

		$log = new Log();
        $log->performed_task = 'Removeu o Material: ' . $material->name. ' ao Material Composto: ' . $composite->name;
       	$log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
        $log->save();

		$log = new Log();
        $log->performed_task = 'Removeu o Material: ' . $material->name. ' ao Material Composto: ' . $composite->name;
        $log->done_by = Auth::user()->id;
		$log->material_id = $composite->id;
        $log->save();

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function upMaterial(Material $composite, Material $material)
	{
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$aboveMaterial = $composite->materials()->where('order', $orderOfMaterial - 1)->first();
		$composite->materials()->updateExistingPivot($material->id, ['order' => $orderOfMaterial - 1]);
		$composite->materials()->updateExistingPivot($aboveMaterial->id, ['order' => $orderOfMaterial]);

		$log = new Log();
        $log->performed_task = 'Colocou o Material: ' . $material->name. ' uma posição acima na lista de materiais do Material Composto: ' . $composite->name;
        $log->done_by = Auth::user()->id;
		$log->material_id = $composite->id;
        $log->save();

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function downMaterial(Material $composite, Material $material)
	{
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$aboveMaterial = $composite->materials()->where('order', $orderOfMaterial + 1)->first();
		$composite->materials()->updateExistingPivot($material->id, ['order' => $orderOfMaterial + 1]);
		$composite->materials()->updateExistingPivot($aboveMaterial->id, ['order' => $orderOfMaterial]);

		$log = new Log();
        $log->performed_task = 'Colocou o Material: ' . $material->name. ' uma posição abaixo na lista de materiais do Material Composto: ' . $composite->name;
        $log->done_by = Auth::user()->id;
		$log->material_id = $composite->id;
        $log->save();

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public static function changeTypeFormat($material)
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

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('materialName', $request->input('materialName'));
        $request->session()->put('materialType', $request->input('materialType'));
        $request->session()->put('materialCreator', $request->input('materialCreator'));
		$request->session()->put('materialSort', $request->input('materialSort'));
        $request->session()->put('materialPages', $request->input('materialPages'));
        $request->session()->put('materialBlocked', $request->input('materialBlocked'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['materialName'] = $request->session()->get('materialName');
        $searchData['materialType'] = $request->session()->get('materialType');
        $searchData['materialCreator'] = $request->session()->get('materialCreator');
        $searchData['materialSort'] = $request->session()->get('materialSort');
        $searchData['materialPages'] = $request->session()->get('materialPages');
		$searchData['materialBlocked'] = $request->session()->get('materialBlocked');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('materialName') && !$request->has('materialType')
            && !$request->has('materialCreator') && !$request->has('materialSort')
            && !$request->has('materialPages') && !$request->has('materialBlocked')) {
            return true;
        }
        return false;
    }

	//autenticado pelo laravel
	public function showMaterial(Material $material)
	{
		if ($material->type == 'image' || $material->type == 'video' || $material->type == 'annex') {
			$content = Storage::get($material->path);
			$whatIWant = substr($material->path, strpos($material->path, ".") + 1);
			if ($material->type == 'image') {
				$contentType = 'image/'.$whatIWant;
			} else if ($material->type == 'video') {
				$contentType = 'video/'.$whatIWant;
				return response()->file(storage_path().'/app/videos/'.$material->name.$material->mime, ['Content-Type: '.$contentType]);
			} else if ($material->type == 'annex') {
				$contentType = 'application/'.$whatIWant;
			}
		} else {
			abort(404);
		}

		return response($content)->header('Content-Type', $contentType);
	}

	//nao autenticado pelo laravel
	public function showMaterialAPI(Material $material)
	{
		if ($material->type == 'image' || $material->type == 'video' || $material->type == 'annex') {
			$content = Storage::get($material->path);
			$whatIWant = substr($material->path, strpos($material->path, ".") + 1);
			if ($material->type == 'image') {
				$contentType = 'image/'.$whatIWant;
			} else if ($material->type == 'video') {
				$contentType = 'video/'.$whatIWant;
				return response()->file(storage_path().'/app/videos/'.$material->name.$material->mime, ['Content-Type: '.$contentType]);
			} else if ($material->type == 'annex') {
				$contentType = 'application/'.$whatIWant;
			}
		} else {
			abort(404);
		}
		 
		return response($content)->header('Content-Type', $contentType);
	}
}
