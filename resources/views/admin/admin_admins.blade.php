@extends ('layouts.master')

@section('title', 'Lista de Administradores')

@section ('content')

	<div class="container">
	    <a class="btn btn-primary" href="{{route('create_user', ['role' =>'admin'])}}">Adicionar Novo Administrador</a>
	    <div class="pull-right"> 
	    </div>
	

	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($admins as $admin)
        <tr>
            <td> {{ $admin->username }} </td>
            <td> {{ $admin->name }} </td>
            <td> {{ $admin->email }} </td>
            <td>
            <a class="btn btn-primary" href="{{route('update_user', ['id' => $admin->id])}}">Editar Administrador</a>
            </td>
            <td>
            <form action="{{route('users.deleteAdmin', ['id' => $admin->id])}}" method="post" class="inline">
                {{ csrf_field() }}
            <button type="submit" class="btn btn-xs btn-danger">Apagar Administrador</button>
            </form>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection