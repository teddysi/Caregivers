@extends ('layouts.master')

@section('title', 'Lista de Cuidadores')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="{{route('create_user', ['role' =>'caregiver'])}}">Adicionar Novo Cuidador</a>
	    <div class="pull-right"> 
	    </div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Pacientes</th>
            <th></th>
            <th></th>
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
            <a class="btn btn-primary" href="{{route('update_user', ['id' => $caregiver->id])}}">Editar Cuidador</a>
            </td>
            <td>
            <form action="{{route('users.deleteCaregiver', ['id' => $caregiver->id])}}" method="post" class="inline">
                {{ csrf_field() }}
            <button type="submit" class="btn btn-xs btn-danger">Apagar Cuidador</button>
            </form>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection