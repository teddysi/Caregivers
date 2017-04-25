@extends ('layouts.master')

@section('title', 'Lista de Necessidades')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="{{route('needs.create_need')}}">Adicionar Nova Necessidade</a>
	    <div class="pull-right"> 
	</div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Descrição</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($needs as $need)
    	<tr>
            <td> {{ $need->description }} </td>
            <td> 
            <a class="btn btn-primary" href="{{route('admin.admin_need_materials', ['id' => $need->id])}}">Ver Materiais</a> </td>
            <td>
            <a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['id' => $need->id]) }}">Editar</a>
            </td>
            <td>
            <a class="btn btn-danger" href="">Remover Necessidade</a>
            </td>
    	</tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection