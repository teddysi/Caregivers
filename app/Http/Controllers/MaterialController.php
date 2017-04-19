<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Material;
use App\TextFile;
use App\Video;
use App\Image;
use App\EmergencyContact;

class MaterialController extends Controller
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

    public function createMaterial($type)
	{	
		$material = new Material();
		$material->type = $type;

		return view('materials.create_material', compact('material'));
	}

	public function saveText(Request $request)
	{

		$this->validate($request, [
				'name' => 'required|unique:materials',
				'description' => 'required|unique:materials',
			], $this->messages);

		$material = new TextFile();
		$material->name = $request->input('name');
		$material->description = $request->input('description');

		$material->save();

		return redirect('/materials');
	}

	public function saveVideo(Request $request)
	{

		$this->validate($request, [
				'name' => 'required|unique:materials',
				'description' => 'required|unique:materials',
				'url' => 'required',
			], $this->messages);

		$material = new Video();
		$material->name = $request->input('name');
		$material->description = $request->input('description');
		$material->url = $request->input('url');

		$material->save();

		return redirect('/materials');
	}

	public function saveImage(Request $request)
	{

		$this->validate($request, [
				'name' => 'required|unique:materials',
				'description' => 'required|unique:materials',
			], $this->messages);

		$material = new Image();
		$material->name = $request->input('name');
		$material->description = $request->input('description');

		$material->save();

		return redirect('/materials');
	}

	public function saveContact(Request $request)
	{

		$this->validate($request, [
				'name' => 'required|unique:materials',
				'number' => 'required|unique:materials', /////////FALTA REGEX
			], $this->messages);

		$material = new EmergencyContact();
		$material->description = "";
		$material->name = $request->input('name');
		$material->number = $request->input('number');

		$material->save();

		return redirect('/materials');
	}

	public function deleteMaterial($id)
	{
		$material = Material::find($id);
        $material->delete();

        return redirect('/materials');
	}
}
