@extends('layouts.master')

@section('tittle', 'Criar Utilizador')

@section('content')
<div class="container">
    <form action="{{url('/users/create')}}" method="POST" class="form-group">
        {{ csrf_field() }}
        <input name="role" type="hidden" value="{{ $role }}">

        <div class="form-group">
            <label for="inputUsername">Username</label>
            <input
                type="text" class="form-control"
                name="username" id="inputUsername"
                placeholder="Username" value="{{ old('username') }}" />
        </div>

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Nome" value="{{ old('name') }}" />
        </div>

        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input
                type="email" class="form-control"
                name="email" id="inputEmail"
                placeholder="Email" value="{{ old('email') }}"/>
        </div>

        @if($role == 'healthcarepro')
            <div class="form-group">
                <label for="inputJob">Trabalho/Estatuto</label>
                <input
                    type="text" class="form-control"
                    name="job" id="inputJob"
                    placeholder="Trabalho/Estatuto" value="{{ old('job') }}" />
            </div>

            <div class="form-group">
                <label for="inputFacility">Local de Trabalho</label>
                <input
                    type="text" class="form-control"
                    name="facility" id="inputFacility"
                    placeholder="Local de Trabalho" value="{{ old('facility') }}" />
            </div>
        @endif

        @if($role == 'caregiver')
            <div class="form-group">
                <label for="inputLocation">Localização</label>
                <input
                    type="text" class="form-control"
                    name="location" id="inputLocation"
                    placeholder="Localização" value="{{ old('location') }}" />
            </div>
        @endif

        <div class="form-group">
            <label for="inputPassword">Password</label>
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
            <button type="submit" class="btn btn-primary" name="save">Criar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection