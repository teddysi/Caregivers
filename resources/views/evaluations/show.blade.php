@extends ('layouts.master')

@section('title', 'Avaliação')

@section ('content')
<div class="container">
    <h2><strong>Avaliação:</strong> {{ $evaluation->name }}</h2>
    <h4><strong>Descrição:</strong> {{ $evaluation->description }}</h4>
    <h4><strong>Ficheiro:</strong> <a href="{{ route('evaluations.showContent', ['evaluation' => $evaluation->id] )}}" target="_blank">{{ $evaluation->name.$evaluation->mime }}</a></h4>
    <h4><strong>Criador:</strong> {{ $evaluation->creator->username }}</h4>
    <h4><strong>Data da criação:</strong> {{ $evaluation->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $evaluation->updated_at }}</h4>
    
    <a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a>
</div>

@endsection