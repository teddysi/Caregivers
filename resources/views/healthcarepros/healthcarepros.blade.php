@extends ('layouts.master')

@section('title', 'Lista de Profissionais de Saúde')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="{{route('create_user', ['role' =>'healthcarepro'])}}">Adicionar Novo Profissional de Saúde</a>
	    <div class="pull-right"> 
	</div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Profissão/Estatuto</th>
            <th>Local de Trabalho</th>
            <th>Cuidadores</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($healthcarepros as $healthcarepro)
    	<tr>
    		<td> {{ $healthcarepro->username }} </td>
            <td> {{ $healthcarepro->name }} </td>
            <td> {{ $healthcarepro->email }} </td>
            <td> {{ $healthcarepro->job }} </td>
            <td> {{ $healthcarepro->facility }} </td>
            <td>
                <a class="btn btn-primary" href="{{route('admin.admin_healthcarepro_caregivers', ['id' => $healthcarepro->id])}}">Cuidadores
                </a>
            </td>
            <td>    
                <a class="btn btn-primary" href="{{route('update_user', ['id' => $healthcarepro->id])}}">Editar Cuidador</a>
                <form action="{{route('users.block', ['id' => $healthcarepro->id])}}" method="post" class="inline">
                {{ csrf_field() }}
            @if($healthcarepro->blocked == 0)
                <button type="submit" class="btn btn btn-danger">Bloquear</button>
            @else
                <button type="submit" class="btn btn-success">Desbloquear</button>
            @endif
            </form>
            </td>
    	</tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection