@extends ('layouts.master')

@section('title', 'Lista de Cuidadores')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="">Adicionar Novo Cuidador</a>
	    <div class="pull-right"> 
	    </div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Pacientes</th>
            <th>Acções</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($caregivers as $caregiver)
        <tr>
            <td> {{ $caregiver->username }} </td>
            <td> {{ $caregiver->name }} </td>
            <td> {{ $caregiver->email }} </td>
            <td> 
            <a class="btn btn-primary" href="{{route('admin.admin_caregiver_patients', ['id' => $caregiver->id])}}">Ver pacientes</a> </td>
            <td>
            <a class="btn btn-primary" href="">Editar Cuidador</a>
            <a class="btn btn-danger" href="">Remover Cuidador</a>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection