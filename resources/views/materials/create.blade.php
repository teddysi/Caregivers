@extends('layouts.master')

@section('tittle', 'Criar Material')

@section('content')

<div class="container">
    @if ($type == 'text')
        <legend>Novo Texto</legend>
    @elseif ($type == 'image')
        <legend>Nova Imagem</legend>
    @elseif ($type == 'video')
        <legend>Novo Video</legend>
    @elseif ($type == 'emergencyContact')
        <legend>Novo Contacto de Emergência</legend>
    @elseif ($type == 'annex')
        <legend>Novo Anexo</legend>
    @else
        <legend>Novo Material Composto</legend>
    @endif
    @if ($type == 'composite')
        <form action="{{url('/materials/add')}}" method="POST" class="form-group">
    @else
        <form action="{{url('/materials/create')}}" method="POST" class="form-group" enctype="multipart/form-data">
    @endif
        {{ csrf_field() }}
        <input name="type" type="hidden" value="{{ $type }}">

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ old('name') }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ old('description') }}" />
        </div>

        @if ($type == 'text')
            <div class="form-group">
                <label for="inputBody">Texto</label>
                <textarea class="form-control" rows="5" 
                    type="text" name="body" 
                    id="body" placeholder="Texto">{{ old('body') }}</textarea>
            </div>
        @endif

        @if ($type == 'image')
            <div class="form-group">
                <label for="inputFile">Ficheiro</label>
                <input type="file" name="pathImage" accept="image/*"/>
            </div>
        @endif

        @if ($type == 'video')
            <div class="form-group">
                <label for="inputFile">Ficheiro</label>
                <input type="file" name="pathVideo" accept="video/mp4"/>
            </div>
        @endif

        @if ($type == 'annex')
            <div class="form-group">
                <label for="selectType">Tipo</label>
                <select name="selectType" id="selectType" class="form-control selectpicker" onchange="selectTypeChange()">
                    <option value="">Escolha um Tipo</option>
                    <optgroup label="Anexo Externo">
                        <option value="0" {{ old('selectType') == 0 ? 'selected' : '' }}>Video Externo</option>
                        <option value="1" {{ old('selectType') == 1 ? 'selected' : '' }}>Link para Website</option>
                    </optgroup>
                    <optgroup label="Anexo Interno">
                        <option value="2"{{ old('selectType') == 2 ? 'selected' : '' }}>Ficheiro (PDF, docx, ...)</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group" id="inputURL" style="display:none">
                <label for="inputURL">URL</label>
                <input
                    type="url" class="form-control"
                    name="url" id="inputURL"
                    placeholder="URL" value="{{ old('url') }}" />
            </div>
            <div class="form-group" id="inputFile" style="display:none">
                <label for="inputFile">Ficheiro</label>
                <input type="file" name="pathAnnex"/>
            </div>
        @endif

        @if ($type == 'emergencyContact')
            <div class="form-group">
                <label for="inputNumber">Número</label>
                <input
                    type="text" class="form-control"
                    name="number" id="inputNumber"
                    placeholder="Número" value="{{ old('number') }}" />
            </div>
        @endif

        <div class="form-group">
            @if ($type == 'composite')
                <button type="submit" class="btn btn-primary" name="save">Adicionar Materiais</button>
            @else
                <button type="submit" class="btn btn-primary" name="save">Criar</button>
            @endif
            <a class="btn btn-default" href="{{ route('materials') }}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection

@section('custom_js')
    <script src="{{ asset('js/annex_select.js') }}"></script>
@endsection 
