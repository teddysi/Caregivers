@extends ('layouts.master')

@section('title', 'Lista de Pacientes')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="">Adicionar Novo Paciente</a>
	    <div class="pull-right"> 
	    </div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($patients as $patient)
        <tr>
            <td> {{ $patient->name }} </td>
            <td> {{ $patient->email }} </td>
            <td> 
            <a class="btn btn-primary" href="{{route('admin.admin_patient_needs', ['id' => $patient->id])}}">Ver Necessidades</a> 
            </td>
            <td>
            <a class="btn btn-danger" href="">Remover Paciente</a>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection