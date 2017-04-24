@extends('layouts.master')

@section('tittle', 'Create patient')

@section('content')

<div class="container">
    <form action="{{url('/patients/save_patient')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputFullname">Nome</label>
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
            placeholder="Email address" value="{{ $patient->email }}"/>
        </div>

        <div class="form-group">
            <label for="inputLocation">Localização</label>
            <input
                type="text" class="form-control"
                name="location" id="inputLocation"
                placeholder="Porto" value="{{ $patient->location }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/patients')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>
@endsection
