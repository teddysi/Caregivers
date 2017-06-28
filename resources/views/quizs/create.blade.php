@extends('layouts.master')

@section('tittle', 'Questionário')

@section('content')

<div class="container">
    <legend>Novo Questionário</legend>
    <form action="{{route('quizs.create')}}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Nome" value="{{ old('name') }}" />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar Questões</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
