<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Material;
use App\TextFile;
use App\Video;
use App\Image;
use App\EmergencyContact;
use App\Composite;
use App\User;
use Storage;
use Response;
use DB;

class MaterialsController extends Controller
{
    private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
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
				'path' => 'nullable|required_if:type,textFile|required_if:type,image',
				'url' => 'nullable|url|required_if:type,video',
				'number' => 'nullable|required_if:type,emergencyContact',
		], $this->messages);

		$material;
		switch ($request->input('type')) {
			case 'textFile':
				$material = new TextFile();
				$originalName = $request->path->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->path = $request->file('path')->storeAs('textFiles', $material->name . '.' . $whatIWant);
				break;

			case 'image':
				$material = new Image();
				$originalName = $request->path->getClientOriginalName();
				$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
				$material->path = $request->file('path')->storeAs('images', $material->name . '.' . $whatIWant);
				break;

			case 'video':
				$material = new Video();
				$material->url = $request->input('url');
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

		return view('materials.show', compact('material', 'compositeMaterials'));
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
			'path' => 'nullable|required_if:type,textFile|required_if:type,image',
			'url' => 'nullable|url|required_if:type,video',
			'number' => 'nullable|required_if:type,emergencyContact',
		], $this->messages);

		$material->name = $request->input('name');
		$material->description = $request->input('description');
		
		$material->save();

		return redirect('/');
	}

	public function toggleBlock(Request $request, Material $material)
	{
		if ($material->blocked == 0) {
            $material->blocked = 1;
            $material->save();

            //$request->session()->flash('blockedStatus', "Material $material->name blocked.");
        } elseif ($material->blocked == 1) {
            $material->blocked = 0;
            $material->save();

            //$request->session()->flash('blockedStatus', "Material $material->name unblocked.");
        }

        return back();
	}

	public function materials(Material $material)
	{
		$compositeMaterials = $material->materials()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10);
		$compositeMaterials->setPageName('compositeMaterials');

		$notCompositeMaterials = Material::whereNotIn('id', $material->materials->modelKeys())
									->where('type', '<>', 'composite')
									->paginate(10);
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

		return redirect()->route('materials.materials', ['material' => $composite->id]); 
	}

	public function addMaterial(Material $composite, Material $material)
	{
		$count = count($composite->materials()->get());
		$composite->materials()->attach([$material->id => ['order'=> $count + 1]]);

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function removeMaterial(Material $composite, Material $material)
	{
		// TO DO: Quando removo um material falta atualizar a ordem na BD
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$composite->materials()->detach($material->id);
		$materialsToUpdateOrder = $composite->materials()->where('order', '>', $orderOfMaterial)->get();
		foreach ($materialsToUpdateOrder as $materialToUpdate) {
			$orderOfMaterialToUpdate = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $materialToUpdate->id]])->first()->order;
			$composite->materials()->updateExistingPivot($materialToUpdate->id, ['order' => $orderOfMaterialToUpdate - 1]);
		}

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function upMaterial(Material $composite, Material $material)
	{
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$aboveMaterial = $composite->materials()->where('order', $orderOfMaterial - 1)->first();
		$composite->materials()->updateExistingPivot($material->id, ['order' => $orderOfMaterial - 1]);
		$composite->materials()->updateExistingPivot($aboveMaterial->id, ['order' => $orderOfMaterial]);

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public function downMaterial(Material $composite, Material $material)
	{
		$orderOfMaterial = DB::table('composite_material')->select('order')->where([['composite_id', $composite->id], ['material_id', $material->id]])->first()->order;
		$aboveMaterial = $composite->materials()->where('order', $orderOfMaterial + 1)->first();
		$composite->materials()->updateExistingPivot($material->id, ['order' => $orderOfMaterial + 1]);
		$composite->materials()->updateExistingPivot($aboveMaterial->id, ['order' => $orderOfMaterial]);

        return redirect()->route('materials.materials', ['composite' => $composite->id]); 
	}

	public static function changeTypeFormat($material)
	{
		switch ($material->type) {
			case 'textFile':
				$material->type = 'Ficheiro de Texto';
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

	public function showMaterial(Material $material)
	{
		$content = Storage::get($material->path);
		$whatIWant = substr($material->path, strpos($material->path, ".") + 1);
		$var =  '.' . $whatIWant;
		return response($content)->header('Content-Type', $var );
	}
}
