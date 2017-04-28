<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Need;
use App\Http\Controllers\UsersController;

class NeedsController extends Controller
{
	private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

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

		return redirect('/');
	}

	public function edit(Need $need) {
		return view('needs.edit', compact('need'));
	}

	public function update(Request $request, Need $need)
	{
		$this->validate($request, [
			'description' => 'required|min:5|unique:needs',
		], $this->messages);

		$need->description = $request->input('description');

		$need->save();

		return redirect('/');
	}

	public function materials(Need $need)
	{
		$materials = $need->materials()->paginate(10);
        UsersController::changeTypeFormat($materials);

        return view('needs.materials',  compact('need', 'materials')); 
	}

}
