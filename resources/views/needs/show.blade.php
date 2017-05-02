@extends ('layouts.master')

@section('title', 'Necessidade')

@section ('content')
<div class="container">
    <h2><strong>Necessidade:</strong> {{ $need->description }}</h2>
    <h4><strong>Criador:</strong> {{ $need->creator->username }}</h4>
    <h4><strong>Data da criação:</strong> {{ $need->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $need->updated_at }}</h4>
    <p><a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a></p>
</div>

@endsection