@extends ('layouts.master')

@section('title', 'Lista de Profissionais de Saúde')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="">Adicionar Novo Profissional de Saúde</a>
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
            <th>Acções</th>
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
            <td><a class="btn btn-primary" href="{{route('admin.admin_healthcarepro_caregivers', ['id' => $healthcarepro->id])}}">Cuidadores</a></td>
            <td>
            <a class="btn btn-danger" href="">Remover Profissional de Saúde</a>
            </td>
    	</tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection