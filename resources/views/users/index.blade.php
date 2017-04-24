@extends ('layouts.master')

@section('title', 'Lista de Todos os Utilziadores')

@section ('content')

	<div class="container">
	<h2>Todos os Utilziadores</h2>
	<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td> {{ $user->username }} </td>
            <td> {{ $user->name }} </td>
            <td> {{ $user->email }} </td>
            <td>
            <a class="btn btn-primary" href="{{route('admin.admin_user_details', ['id' => $user->id])}}">Detalhes</a>
            </td>
        </tr>
    @endforeach
    </tbody>
   

</table>
</div>

@endsection