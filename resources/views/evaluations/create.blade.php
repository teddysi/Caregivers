@extends('layouts.master')

@section('tittle', 'Avaliar')

@section('content')

<div class="container">
    <legend>Nova Avaliação</legend>
    @if (str_contains(Request::url(), '/patients/'))
        <form action="{{route('evaluations.createForPatient', ['id' => $id])}}" method="POST" class="form-group" enctype="multipart/form-data">
    @else 
        <form action="{{route('evaluations.createForCaregiver', ['id' => $id])}}" method="POST" class="form-group" enctype="multipart/form-data">
    @endif
        {{ csrf_field() }}
        <input name="typeEval" type="hidden" value="{{ $typeEval }}">

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ old('description') }}" />
        </div>
        
        <div class="form-group">
            <label for="inputDescription">Tipo de Avaliação</label>
            <input
                type="text" class="form-control"
                name="type" id="inputType"
                placeholder="Tipo de Avaliação" value="{{ old('type') }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Modelo</label>
            <input
                type="text" class="form-control"
                name="model" id="inputModel"
                placeholder="Modelo" value="{{ old('model') }}" />
        </div>

        <div class="form-group">
            <label for="inputFile">Ficheiro</label>
            <input type="file" name="path"/>
        </div>
       
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Submeter Avaliação</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
