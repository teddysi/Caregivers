@extends('layouts.master')

@section('tittle', 'Editar Necessidade')

@section('content')

<div class="container">
    <legend>Editar Necessidade</legend>
    <form action="{{ url('/needs', ['need' => $need->id]) }}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ $need->description }}" />
        </div>
       
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
