@extends ('layouts.master')

@section('title', 'Detalhes')

@section ('content')

<div class="container">
	
    <h2>Detalhes de {{$user->username}}</h2>

    <br>
    
    <p>Username: {{$user->username}}</p>

    <p>Nome: {{$user->name}}</p>

    <p>Email: {{$user->email}}</p>

    <p>Tipo de utilizador: {{$role}}

    @if($user->role == 'healthcarepro')
    <p>Trabalho/Estatuto: {{$user->job}}</p>
	
    <p>Local de Trabalho: {{$user->facility}}</p>
    @endif

    @if($user->role == 'caregiver')
    <p>Classificação: {{$user->rate}}</p>
    
    <p>Número de logins: </p>
    @endif

</div>

@endsection