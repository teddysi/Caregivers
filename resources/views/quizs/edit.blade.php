@extends('layouts.master')

@section('tittle', 'Questionário')

@section('content')

<div class="container">
    <legend>Editar Questionário</legend>
    <form action="{{ url('/quizs', ['quiz' => $quiz->id]) }}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Nome" value="{{ $quiz->name }}" />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="{{ route('quizs') }}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
