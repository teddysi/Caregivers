@extends('layouts.master')

@section('tittle', 'Criar Utente')

@section('content')
<div class="container">
    <legend>Novo Utente</legend>
    <form action="{{url('/patients/create')}}" method="POST" class="form-group">
        {{ csrf_field() }}

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

        <div class="form-group">
            <label for="inputLocation">Localização</label>
            <input
                type="text" class="form-control"
                name="location" id="inputLocation"
                placeholder="Localização" value="{{ old('location') }}"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Criar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection