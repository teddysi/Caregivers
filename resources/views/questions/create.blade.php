@extends('layouts.master')

@section('tittle', 'Perguntas')

@section('content')

<div class="container">
        <form action="{{route('questions.create')}}" method="POST" class="form-group" enctype="multipart/form-data">

        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputQuestion">Nome</label>
            <input
                type="text" class="form-control"
                name="question" id="inputQuestion"
                placeholder="Pergunta?" value="{{ old('question') }}" />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Gravar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
