@extends('layouts.master')

@section('tittle', 'Create user')

@section('content')

@if($user->role == 'admin')
    <div class="container">
    <form action="{{url('/users/create_admin')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputUsername">Username</label>
            <input
                type="text" class="form-control"
                name="username" id="inputUsername"
                placeholder="Username" value="{{ $user->username }}" />
        </div>

        <div class="form-group">
            <label for="inputFullname">Nome</label>
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
            placeholder="Email address" value="{{ $user->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value="{{$user->password}}"/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/admins')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif

@if($user->role == 'healthcarepro')
    <div class="container">
    @if(count($errors) > 0)
        @include('layouts.errors')
    @endif
    <form action="{{url('/users/create_healthcarepro')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputUsername">Username</label>
            <input
                type="text" class="form-control"
                name="username" id="inputUsername"
                placeholder="Username" value="{{ $user->username }}" />
        </div>

        <div class="form-group">
            <label for="inputFullname">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputFullname"
                placeholder="Name" value="{{ $user->name }}" />
        </div>

        <div class="form-group">
        <label for="inputEmail">Email</label>
        <input
            type="email" class="form-control"
            name="email" id="inputEmail"
            placeholder="Email address" value="{{ $user->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value="{{$user->password}}"/>
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
                placeholder="trabalho/estatuto" value="{{ $user->job }}" />
        </div>

        <div class="form-group">
            <label for="inputFacility">Local</label>
            <input
                type="text" class="form-control"
                name="facility" id="inputFacility"
                placeholder="Hospital de Leiria" value="{{ $user->facility }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/healthcarepros')}}">Cancelar</a>
        </div>
    </form>
    </div>
@endif

@if($user->role == 'caregiver')
    <div class="container">
    <form action="{{url('/users/create_caregiver')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputUsername">Username</label>
            <input
                type="text" class="form-control"
                name="username" id="inputUsername"
                placeholder="Username" value="{{ $user->username }}" />
        </div>

        <div class="form-group">
            <label for="inputFullname">Nome</label>
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
            placeholder="Email address" value="{{ $user->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control"
                name="password" id="inputPassword"
                value="{{$user->password}}"/>
        </div>

        <div class="form-group">
            <label for="inputPasswordConfirmation">Confirmar Password</label>
            <input
                type="password" class="form-control"
                name="password_confirmation" id="inputPasswordConfirmation"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/caregivers')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif
@endsection

