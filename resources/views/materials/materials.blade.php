@extends ('layouts.master')

@section('title', 'Lista de Materiais')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="{{route('materials.create_material', ['type' =>'textFile'])}}">Adicionar Novo Material de Texto</a>
	    <div class="pull-right"> 
	</div>
	
    <br>
    <h2>PDF's/Tutoriais/FAQ</h2>
	<table class="table table-striped">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Nome</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materials as $material)
        @if($material->type == 'textFile')
    	<tr>
            <td> {{ $material->description }} </td>
            <td> {{ $material->name }} </td>
            <td><a class="btn btn-danger" href="">Remover Material</a></td>
    	</tr>
        @endif
    @endforeach
    </tbody>
    </table>
    </div>

    <br>
    <div class="container">
        <a class="btn btn-primary" href="{{route('materials.create_material', ['type' =>'image'])}}">Adicionar Nova Imagem</a>
        <div class="pull-right"> 
    </div>
    <h2>Imagens</h2>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Link</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materials as $material)
        @if($material->type == 'image')
        <tr>
            <td> {{ $material->description }} </td>
            <td> <a href="{{ $material->path }}"> {{ $material->name }}</td>
            <td> <a class="btn btn-danger" href="">Remover Material</a></td>
        </tr>
        @endif
    @endforeach
    </tbody>
    </table>
    </div>

    <br>
    <div class="container">
        <a class="btn btn-primary" href="{{route('materials.create_material', ['type' =>'video'])}}">Adicionar Novo Video</a>
        <div class="pull-right"> 
    </div>
    <h2>Videos</h2>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Link</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materials as $material)
        @if($material->type == 'video')
        <tr>
            <td> {{ $material->description }} </td>
            <td> <a href="{{ $material->url }}"> {{ $material->name }}</td>
            <td> <a class="btn btn-danger" href="">Remover Material</a></td>
        </tr>
        @endif
    @endforeach
    </tbody>
    </table>
    </div>

    <br>
    <div class="container">
        <a class="btn btn-primary" href="{{route('materials.create_material', ['type' =>'emergencyContact'])}}">Adicionar Novo Contacto de Emergência</a>
        <div class="pull-right"> 
    </div>
    <h2>Contacto de Emergência</h2>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Número</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materials as $material)
        @if($material->type == 'emergencyContact')
        <tr>
            <td> {{ $material->name }} </td>
            <td> {{ $material->number }}</td>
            <td> <a class="btn btn-danger" href="">Remover Material</a></td>
        </tr>
        @endif
    @endforeach
    </tbody>
    </table>
    </div>
    

</div>

@endsection