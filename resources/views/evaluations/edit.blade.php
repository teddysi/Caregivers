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
            @if (str_contains(Request::url(), '/patients/'))
                <a class="btn btn-default" href="{{ route('patients.show', ['patient' => $evaluation->patient_id]) }}">Cancelar</a>
            @elseif (str_contains(Request::url(), '/materials/'))
                @if ($evaluation->answered_by && !$evaluation->submitted_by)
                    <a class="btn btn-default" href="{{ route('materials.rate', ['caregiver' => $evaluation->answered_by, 'material' => $evaluation->material_id]) }}">Cancelar</a>
                @elseif (!$evaluation->answered_by && $evaluation->submitted_by)
                    <a class="btn btn-default" href="{{ route('materials.rate', ['caregiver' => $evaluation->submitted_by, 'material' => $evaluation->material_id]) }}">Cancelar</a>
                @endif
            @else
                <a class="btn btn-default" href="{{ route('caregivers.rate', ['caregiver' => $evaluation->caregiver_id]) }}">Cancelar</a>
            @endif
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection