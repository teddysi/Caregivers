@extends('layouts.master')

@section('tittle', 'Editar Avaliação')

@section('content')

<div class="container">
    <h2>Editar Avaliação</h2>
    <form action="{{ url('/evaluations', ['evaluation' => $evaluation->id] )}}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                value="{{ $evaluation->name }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                value="{{ $evaluation->description }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection