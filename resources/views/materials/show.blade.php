@extends ('layouts.master')

@section('title', 'Material')

@section ('content')
<div class="container">
    <h2><strong>Material:</strong> {{ $material->name }}</h2>
    <h4><strong>Tipo:</strong> {{ $material->type }}</h4>
    <h4><strong>Descrição:</strong> {{ $material->description }}</h4>
    @if ($material->type == 'Ficheiro de Texto' || $material->type == 'Imagem')
        <h4><strong>Localização:</strong> <a href="{{ route('materials.lelito', ['material' => $material->id] )}}" target="_blank">{{ $material->path }}</a></h4>
    @endif
    @if ($material->type == 'Video')
        <h4><strong>URL:</strong> <a href="{{ $material->url }}" target="_blank">{{ $material->url }}</a></h4>
    @endif
    @if ($material->type == 'Contacto de Emergência')
        <h4><strong>Número:</strong> {{ $material->number }}</h4>
    @endif
    <h4><strong>Criador:</strong> {{ $material->creator->username }}</h4>
    <h4><strong>Data da criação:</strong> {{ $material->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $material->updated_at }}</h4>
    <p><a class="btn btn-default" href="{{ url()->previous() }}">Voltar a atrás</a></p>
</div>

@endsection