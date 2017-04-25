<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Need;
use Auth;


class NeedController extends Controller
{

	private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

	public function needs()
	{
		$needs = Need::all();

		return view('needs.needs', compact('needs'));
	}

    public function createNeed()
	{	
		$need = new Need();

		return view('needs.create_need', compact('need'));
	}

	public function saveNeed(Request $request)
	{

		$this->validate($request, [
				'description' => 'required|unique:needs',
			], $this->messages);

		$need = new Need($request->all());
		$need->created_by = Auth::user()->id;
		$need->save();

		return redirect('/needs');
	}

	public function deleteNeed($id)
	{
		$need = Need::find($id);
        $need->delete();

        return redirect('/needs');
	}

	public function needMaterials($id)
	{
		$materials = Need::find($id)->materials;

		return view('needs.need_materials', compact('materials'));
	}

	public function edit($id) {
		$need = Need::find($id);
		return view('needs.edit', compact('need'));
	}

	public function update(Request $request, Need $need)
	{
		$this->validate($request, [
			'description' => 'required',
		], $this->messages);

		$need->description = $request->description;

		$need->save();

		return redirect('/needs');
	}

}
