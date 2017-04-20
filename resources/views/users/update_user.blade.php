@extends('layouts.master')

@section('tittle', 'Update user')

@section('content')

@if($updateUser->role == 'admin')
    <div class="container">
    <form action="{{url('/users/update_admin', ['id' => $updateUser->id])}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputFullname">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $updateUser->name }}" />
        </div>

        <div class="form-group">
        <label for="inputEmail">Email</label>
        <input
            type="email" class="form-control"
            name="email" id="inputEmail"
            placeholder="Email address" value="{{ $updateUser->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Nova Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value=""/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="{{url('/admins')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif

@if($updateUser->role == 'healthcarepro')
    <div class="container">
    <form action="{{url('/users/update_admin', ['id' => $updateUser->id])}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputFullname">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputFullname"
                placeholder="Name" value="{{ $updateUser->name }}" />
        </div>

        <div class="form-group">
        <label for="inputEmail">Email</label>
        <input
            type="email" class="form-control"
            name="email" id="inputEmail"
            placeholder="Email address" value="{{ $updateUser->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value=""/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"/>
        </div>

        <div class="form-group">
            <label for="inputJob">Trabalho/Estatuto</label>
            <input
                type="text" class="form-control"
                name="job" id="inputJob"
                placeholder="trabalho/estatuto" value="{{ $updateUser->job }}" />
        </div>

        <div class="form-group">
            <label for="inputFacility">Local</label>
            <input
                type="text" class="form-control"
                name="facility" id="inputFacility"
                placeholder="Hospital de Leiria" value="{{ $updateUser->facility }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="{{url('/healthcarepros')}}">Cancelar</a>
        </div>
        @include('layouts.errors')
    </form>
    </div>
@endif

@if($updateUser->role == 'caregiver')
    <div class="container">
    <form action="{{url('/users/update_admin', ['id' => $updateUser->id])}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputFullname">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $updateUser->name }}" />
        </div>

        <div class="form-group">
        <label for="inputEmail">Email</label>
        <input
            type="email" class="form-control"
            name="email" id="inputEmail"
            placeholder="Email address" value="{{ $updateUser->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value=""/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="{{url('/caregivers')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif
@endsection

