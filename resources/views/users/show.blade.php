@extends ('layouts.master')

@section('title', 'Utilizador')

@section ('content')
<div class="container">
    <h2><strong>Utilizador:</strong> {{ $user->username }}</h2>
    <h4><strong>Nome:</strong> {{ $user->name }}</h4>
    <h4><strong>Email:</strong> {{ $user->email }}</h4>
    <h4><strong>Função:</strong> {{ $user->role }}</h4>
    @if ($user->role == 'Profissional de Saúde')
        <h4><strong>Trabalho/Estatuto:</strong> {{ $user->job }}</h4>
        <h4><strong>Local de Trabalho:</strong> {{ $user->facility }}</h4>
    @endif
    @if ($user->role == 'Cuidador')
        <h4><strong>Localização:</strong> {{ $user->location }}</h4>
        <h4><strong>Classificação:</strong> {{ $user->rate }}</h4>
        <h4><strong>Criador:</strong> {{ $user->creator->username }}</h4>
    @endif
    <h4><strong>Data da criação:</strong> {{ $user->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $user->updated_at }}</h4>
    <p><a class="btn btn-default" href="{{ url()->previous() }}">Voltar a atrás</a></p>
</div>

@endsection