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

	public function show($id)
	{
		$material = Material::find($id);
		$this->changeTypeFormat($material);
		return view('materials.show', compact('material'));
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
