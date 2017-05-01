@extends('layouts.master')

@section('tittle', 'Editar Material')

@section('content')

<div class="container">
    <h2>Editar {{ $material->type }}</h2>
    <form action="{{ url('/materials', ['material' => $material->id] )}}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                value="{{ $material->name }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                value="{{ $material->description }}" />
        </div>

        @if ($material->type == 'Ficheiro de Texto' || $material->type == 'Imagem')
            <div class="form-group">
                <label for="inputPath">Localização</label>
                <input
                    type="text" class="form-control"
                    name="path" id="inputPath"
                    value="{{ $material->path }}" />
            </div>
        @endif

        @if ($material->type == 'Video')
            <div class="form-group">
                <label for="inputURL">URL</label>
                <input
                    type="text" class="form-control"
                    name="url" id="inputURL"
                    value="{{ $material->url }}" />
            </div>
        @endif

        @if ($material->type == 'Contacto de Emergência')
            <div class="form-group">
                <label for="inputNumber">Number</label>
                <input
                    type="text" class="form-control"
                    name="number" id="inputNumber"
                    value="{{ $material->number }}" />
            </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection