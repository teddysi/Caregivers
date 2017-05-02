@extends('layouts.master')

@section('tittle', 'Editar Utilizador')

@section('content')

<div class="container">
    <h2>Editar Utilizador: {{ $user->username }}</h2>
    <form action="{{ url('/users', ['user' => $user->id]) }}" method="POST" class="form-group">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $user->name }}" />
        </div>

        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input
                type="email" class="form-control"
                name="email" id="inputEmail"
                placeholder="Email" value="{{ $user->email }}"/>
        </div>

        @if($user->role == 'Profissional de Saúde')
            <div class="form-group">
                <label for="inputJob">Trabalho/Estatuto</label>
                <input
                    type="text" class="form-control"
                    name="job" id="inputJob"
                    placeholder="Trabalho/Estatuto" value="{{ $user->job }}" />
            </div>

            <div class="form-group">
                <label for="inputFacility">Local de Trabalho</label>
                <input
                    type="text" class="form-control"
                    name="facility" id="inputFacility"
                    placeholder="Local de Trabalho" value="{{ $user->facility }}" />
            </div>
        @endif

        @if($user->role == 'Cuidador')
            <div class="form-group">
                <label for="inputLocation">Localização</label>
                <input
                    type="text" class="form-control"
                    name="location" id="inputLocation"
                    placeholder="Localização" value="{{ $user->location }}" />
            </div>
        @endif

        <div class="form-group">
            <label for="inputPassword">Nova Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                placeholder="Password"/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"
                placeholder="Password"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection
