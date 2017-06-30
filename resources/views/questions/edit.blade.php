@extends('layouts.master')

@section('tittle', 'Perguntas')

@section('content')

<div class="container">
    <legend>Editar Questão</legend>
    <form action="{{ url('/questions', ['question' => $question->id]) }}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputQuestion">Questão</label>
            <input
                type="text" class="form-control"
                name="question" id="inputQuestion"
                placeholder="Questão?" value="{{ $question->question }}" />
        </div>
        
        @if($question->type == 'radio')
            <div class="form-group" id="inputOptions">
                <label for="inputOptions">Opções de Resposta</label>
                <h5>Nota: Cada opção deve ser separada e terminada por ";". Exemplo: "Gosto muito;Não gosto;Sim;Não;"</h5>
                <input type="text" class="form-control" name="values" value="{{ $question->values }}"/>
            </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="{{ route('questions') }}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
