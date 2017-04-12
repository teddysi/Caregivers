@extends ('layouts.master')

@section('title', 'Lista de Administradores')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="">Adicionar Novo Administrador</a>
	    <div class="pull-right"> 
	    </div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Acções</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($admins as $admin)
        <tr>
            <td> {{ $admin->username }} </td>
            <td> {{ $admin->name }} </td>
            <td> {{ $admin->email }} </td>
            <td>
            <a class="btn btn-primary" href="">Editar Administrador</a>
            <a class="btn btn-danger" href="">Remover Administrador</a>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection