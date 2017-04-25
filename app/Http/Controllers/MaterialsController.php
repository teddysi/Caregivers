<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Material;
use App\TextFile;
use App\Video;
use App\Image;
use App\EmergencyContact;
use App\User;

class MaterialsController extends Controller
{
    private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

	public function materials()
	{
		$materials = Material::all();

		return view('materials.materials', compact('materials'));
	}

	public function show($id)
	{
		$material = Material::find($id);
		$this->changeTypeFormat($material);
		return view('materials.show', compact('material'));
	}

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('name', $request->input('name'));
        $request->session()->put('type', $request->input('type'));
        $request->session()->put('creator', $request->input('creator'));
		$request->session()->put('sort', $request->input('sort'));
        $request->session()->put('pages', $request->input('pages'));
        $request->session()->put('blocked', $request->input('blocked'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['name'] = $request->session()->get('name');
        $searchData['type'] = $request->session()->get('type');
        $searchData['creator'] = $request->session()->get('creator');
        $searchData['sort'] = $request->session()->get('sort');
        $searchData['pages'] = $request->session()->get('pages');
		$searchData['blocked'] = $request->session()->get('blocked');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('name') && !$request->has('type')
            && !$request->has('creator') && !$request->has('sort')
            && !$request->has('pages') && !$request->has('blocked')) {
            return true;
        }
        return false;
    }

	public function index(Request $request)
	{
		$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['name' => '', 'type' => '', 'creator' => '', 'sort' => '', 'pages' => '', 'blocked' => ''];

        if ($request->has('dashboard')) {
            $this->saveDataFieldsToSession($request);
            $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
        } else {
            $url = $request->fullUrl();
            if ($this->isRequestDataEmpty($request) && str_contains($url, 'materials?page=')) {
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            } else {
                $this->saveDataFieldsToSession($request);
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            }
        }

		if (!empty($searchData['name'])) {
           	$where[] = ['name', 'like', '%'.$searchData['name'].'%'];
        }

        if (!empty($searchData['type'])) {
			if($searchData['type'] != 'all') {
                $where[] = ['type', 'like', '%'.$searchData['type'].'%'];
            }
        }

        if (!empty($searchData['creator'])) {
			$user = User::where('username','like','%'.$searchData['creator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['blocked'])) {
            if($searchData['blocked'] == 'just_blocked') {
                $where[] = ['blocked', 1];
            } elseif($searchData['blocked'] == 'just_unblocked') {
                $where[] = ['blocked', 0];
            }
        }

		if (!empty($searchData['sort'])) {
            if($searchData['sort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['sort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['sort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['sort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            } elseif($searchData['sort'] == 'type_az') {
                $col = 'type';
                $order = 'asc';
            } elseif($searchData['sort'] == 'type_za') {
                $col = 'type';
                $order = 'desc';
            }
        }

		if (!empty($searchData['pages'])) {
            $pages = $searchData['pages'];
        }

		$materials = Material::where($where)->orderBy($col, $order)->paginate((int)$pages);
		foreach ($materials as $material) {
			$this->changeTypeFormat($material);
		}

		return view('materials.index', compact('materials','searchData'));
	}

	public function edit($id) {
		$material = Material::find($id);
		$this->changeTypeFormat($material);
		return view('materials.edit', compact('material'));
	}

	public function update(Request $request, Material $material)
	{
		$this->validate($request, [
			'name' => 'required|min:4',
			'description' => 'required',
			'path' => 'nullable',
			'url' => 'nullable|url',
			'number' => 'nullable',
		], $this->messages);

		$material->name = $request->name;
		$material->description = $request->description;
		$material->path = $request->path;
		$material->url = $request->url;
		$material->number = $request->number;

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

    public function create($type)
	{	
		return view('materials.create', compact('type'));
	}

	public function store(Request $request)
	{	
		$this->validate($request, [
				'name' => 'required|min:4|unique:materials',
				'description' => 'required|min:4',
				'path' => 'nullable',
				'url' => 'nullable|url|required_if:type,video',
				'number' => 'nullable|required_if:type,emergencyContact',
		], $this->messages);

		$material;
		switch ($request->input('type')) {
			case 'textFile':
				$material = new TextFile();
				break;

			case 'image':
				$material = new Image();
				break;

			case 'video':
				$material = new Video();
				break;

			case 'emergencyContact':
				$material = new EmergencyContact();
				break;

			default:
				break;
		}
		
		$material->name = $request->input('name');
		$material->description = $request->input('description');
		$material->path = $request->input('path');

		if ($request->input('type') == 'textFile') {
			$originalName = $request->cenas->getClientOriginalName();
			$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
			$material->path = $request->file('cenas')->storeAs('textFiles', $material->name . '.' . $whatIWant);

		} elseif ($request->input('type') == 'image') {
			$originalName = $request->cenas->getClientOriginalName();
			$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
			$material->path = $request->file('cenas')->storeAs('images', $material->name . '.' . $whatIWant);
		}

		$material->url = $request->input('url');
		$material->number = $request->input('number');
		$material->created_by = Auth::user()->id;
		$material->save();

		return redirect('/materials');
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

			default:
				break;
		}
	}
}
