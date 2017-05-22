@extends('layouts.master')

@section('tittle', 'Perguntas')

@section('content')

<div class="container">
        <form action="{{ url('/questions', ['question' => $question->id]) }}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputQuestion">Pergunta</label>
            <input
                type="text" class="form-control"
                name="question" id="inputQuestion"
                placeholder="Pergunta?" value="{{ $question->question }}" />
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Gravar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
