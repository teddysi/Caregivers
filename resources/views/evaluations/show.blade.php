@extends ('layouts.master')

@section('title', 'Avaliação')

@section ('content')
<div class="container">
    <h4><strong>Descrição:</strong> {{ $evaluation->description }}</h4>
    <h4><strong>Tipo de Avaliação:</strong> {{ $evaluation->type }}</h4>
    <h4><strong>Modelo:</strong> {{ $evaluation->model }}</h4>
    <h4><strong>Ficheiro:</strong> <a href="{{ route('evaluations.showContent', ['evaluation' => $evaluation->id] )}}" target="_blank">{{ $evaluation->name.$evaluation->mime }}</a></h4>
    <h4><strong>Criador:</strong> {{ $evaluation->creator->username }}</h4>
    <h4><strong>Data da criação:</strong> {{ $evaluation->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $evaluation->updated_at }}</h4>
    
    <a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a>
</div>

@endsection