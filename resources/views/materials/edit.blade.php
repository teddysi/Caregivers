@extends('layouts.master')

@section('tittle', 'Editar Material')

@section('content')

<div class="container">
    <legend>Editar {{ $material->type }}</legend>
    <form action="{{ url('/materials', ['material' => $material->id] )}}" method="POST" class="form-group"  enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <input name="type" type="hidden" value="{{ $material->type }}">

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

        @if ($material->type == 'Texto')
            <div class="form-group">
                <label for="inputBody">Texto</label>
                <textarea class="form-control" rows="5" 
                    type="text" name="body"
                    id="inputBody">{{ $material->body }}</textarea>
            </div>
        @endif

        @if ($material->type == 'Imagem')
            <div class="form-group">
                <label for="inputFile">Ficheiro</label>
                <h5>Nota: Caso não pretenda alterar o ficheiro, não necessita selecionar um. Manter-se-á o atual.</h5>
                <input type="file" name="pathImage" accept="image/*"/>
            </div>
        @endif

        @if ($material->type == 'Video')
            <div class="form-group">
                <label for="inputFile">Ficheiro</label>
                <h5>Nota: Caso não pretenda alterar o ficheiro, não necessita selecionar um. Manter-se-á o atual.</h5>
                <input type="file" name="pathVideo" accept="video/mp4"/>
            </div>
        @endif

        @if ($material->type == 'Anexo')
            @if ($material->path == null)
                <div class="form-group">
                    <label for="inputURL">URL</label>
                    <input
                        type="url" class="form-control"
                        name="url" id="inputURL"
                        value="{{ $material->url }}"/>
                </div>
            @else
                <div class="form-group">
                    <label for="inputFile">Ficheiro</label>
                    <h5>Nota: Caso não pretenda alterar o ficheiro, não necessita selecionar um. Manter-se-á o atual.</h5>
                    <input type="file" name="pathAnnex"/>
                </div>
            @endif
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