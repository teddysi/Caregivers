@extends('layouts.master')

@section('tittle', 'Avaliar')

@section('content')

<div class="container">
    @if (str_contains(Request::url(), '/patients/'))
        <legend>Disponibilizar Questionário para o Utente: {{ $patient->name }}</legend>
        <form action="{{route('evaluations.createForPatient', ['id' => $patient->id])}}" method="POST" class="form-group" enctype="multipart/form-data">
    @else
        <legend>Disponibilizar Questionário para o Cuidador: {{ $caregiver->name }}</legend>
        <form action="{{route('evaluations.createForCaregiver', ['id' => $caregiver->id])}}" method="POST" class="form-group" enctype="multipart/form-data">
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
            <label for="inputQuiz">Questionário</label>
            <select name="quiz" class="form-control">
                @foreach($quizs as $quiz)
                    <option value="{{$quiz->id}}">{{$quiz->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Submeter Avaliação</button>
            @if (str_contains(Request::url(), '/patients/'))
                <a class="btn btn-default" href="{{ route('patients.show', ['patient' => $patient->id]) }}">Cancelar</a>
            @else
                <a class="btn btn-default" href="{{ route('caregivers.rate', ['caregiver' => $caregiver->id]) }}">Cancelar</a>
            @endif
        </div>
        
    @include('layouts.errors')
    </form>
</div>

@endsection
