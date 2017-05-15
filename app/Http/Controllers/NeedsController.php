<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Need;
use App\User;
use App\Material;
use App\Http\Controllers\UsersController;
use Auth;

class NeedsController extends Controller
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
        $searchData = ['needDescription' => '', 'needCreator' => '', 'needSort' => '', 'needPages' => ''];

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

		if (!empty($searchData['needDescription'])) {
           	$where[] = ['description', 'like', '%'.$searchData['needDescription'].'%'];
        }

        if (!empty($searchData['needCreator'])) {
			$user = User::where('username','like','%'.$searchData['needCreator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['needSort'])) {
            if($searchData['needSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['needSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['needSort'] == 'description_az') {
                $col = 'description';
                $order = 'asc';
            } elseif($searchData['needSort'] == 'description_za') {
                $col = 'description';
                $order = 'desc';
            }
        }

		if (!empty($searchData['needPages'])) {
            $pages = $searchData['needPages'];
        }

		$needs = Need::where($where)->orderBy($col, $order)->paginate((int)$pages);

		return view('needs.index', compact('needs','searchData'));
	}

	public function show(Need $need)
	{
		return view('needs.show', compact('need'));
	}

    public function create()
	{	
		return view('needs.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'description' => 'required|min:5|unique:needs',
		], $this->messages);

		$need = new Need();
		$need->description = $request->input('description');
		$need->created_by = Auth::user()->id;
		$need->save();

        $log = new Log();
		$log->performed_task = 'Criou a Necessidade: ' . $need->description;
		$log->user_id = Auth::user()->id;
		$log->save();

		return redirect('/');
	}

	public function edit(Need $need) {
		return view('needs.edit', compact('need'));
	}

	public function update(Request $request, Need $need)
	{
		$this->validate($request, [
			'description' => 'required|min:5|unique:needs,description,'.$need->id,
		], $this->messages);

		$need->description = $request->input('description');
		$need->save();

        $log = new Log();
		$log->performed_task = 'Atualizou a Necessidade: ' . $need->description;
		$log->user_id = Auth::user()->id;
		$log->save();

		return redirect('/');
	}

	public function materials(Need $need)
	{
		$materials = $need->materials()->paginate(10);
        UsersController::changeTypeFormat($materials);

        return view('needs.materials',  compact('need', 'materials')); 
	}

    public function diassociateMaterial(Need $need, Material $material)
    {
        if (!$need->materials->contains('id', $material->id)) {
            abort(403);
        }
        $need->materials()->detach($material->id);

        $log = new Log();
		$log->performed_task = 'Desassociou o Material: ' . $material->name. 'da Necessiade: ' . $need->description;
		$log->user_id = Auth::user()->id;
		$log->save();

        return redirect()->route('needs.materials', ['need' => $need->id]); 
    }

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('needDescription', $request->input('needDescription'));
        $request->session()->put('needCreator', $request->input('needCreator'));
		$request->session()->put('needSort', $request->input('needSort'));
        $request->session()->put('needPages', $request->input('needPages'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['needDescription'] = $request->session()->get('needDescription');
        $searchData['needCreator'] = $request->session()->get('needCreator');
        $searchData['needSort'] = $request->session()->get('needSort');
        $searchData['needPages'] = $request->session()->get('needPages');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('needDescription') && !$request->has('needCreator') 
			&& !$request->has('needSort') && !$request->has('needPages')) {
            return true;
        }
        return false;
    }
}
