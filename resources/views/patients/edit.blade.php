@extends('layouts.master')

@section('tittle', 'Editar Utente')

@section('content')

<div class="container">
    <legend>Editar Utente</legend>
    <form action="{{ url('/patients', ['patient' => $patient->id]) }}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $patient->name }}" />
        </div>

        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input
                type="email" class="form-control"
                name="email" id="inputEmail"
                placeholder="Email" value="{{ $patient->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputLocation">Localização</label>
            <input
                type="text" class="form-control"
                name="location" id="inputLocation"
                placeholder="Localização" value="{{ $patient->location }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
