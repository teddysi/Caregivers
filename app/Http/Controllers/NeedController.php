<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Need;


class NeedController extends Controller
{

	private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

	public function needs()
	{
		$needs = Need::all();

		return view('admin.admin_needs', compact('needs'));
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

		$need->save();

		return redirect('/needs');
	}

	public function deleteNeed($id)
	{
		$need = Need::find($id);
        $need->delete();

        return redirect('/needs');
	}

}
