@extends('layouts.master')

@section('tittle', 'Avaliar')

@section('content')

<div class="container">
    <legend>Disponibilizar Questionário para o Material: {{ $material->name }}</legend>
	<form action="{{route('evaluations.storeForMaterial', ['material' => $material->id])}}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="caregiver" type="hidden" value="{{ $id }}">

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
            <label for="inputQuiz">Questionário</label>
            <select name="quiz" class="form-control">
                @foreach($quizs as $quiz)
                <option value="{{$quiz->id}}">{{$quiz->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Submeter Avaliação</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
