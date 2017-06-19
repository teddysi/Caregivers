@extends('layouts.master')

@section('tittle', 'Editar Avaliação')

@section('content')

<div class="container">
    <legend>Editar Avaliação</legend>
    <form action="{{ url('/evaluations', ['evaluation' => $evaluation->id] )}}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                value="{{ $evaluation->description }}" />
        </div>

        <div class="form-group">
            <label for="inputType">Tipo de Avaliação</label>
            <input
                type="text" class="form-control"
                name="type" id="inputType"
                value="{{ $evaluation->type }}" />
        </div>

        <div class="form-group">
            <label for="inputModel">Modelo</label>
            <input
                type="text" class="form-control"
                name="model" id="inputModel"
                value="{{ $evaluation->model }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection