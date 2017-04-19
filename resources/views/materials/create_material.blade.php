@extends('layouts.master')

@section('tittle', 'Create material')

@section('content')

@if($material->type == 'textFile')
    <div class="container">
    <form action="{{url('/materials/create_text')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $material->name }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ $material->description }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/materials')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif

@if($material->type == 'image')
    <div class="container">
    <form action="{{url('/materials/create_image')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $material->name }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ $material->description }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/materials')}}">Cancelar</a>
        </div>
        @include('layouts.errors')
    </form>
    </div>
@endif

@if($material->type == 'video')
    <div class="container">
    <form action="{{url('/materials/create_video')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $material->name }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ $material->description }}" />
        </div>

        <div class="form-group">
            <label for="inputUrl">Url</label>
            <input
                type="text" class="form-control"
                name="url" id="inputUrl"
                placeholder="http://youtube.com/fadsx/228/?52725" value="{{ $material->url }}"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/materials')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif

@if($material->type == 'emergencyContact')
    <div class="container">
    <form action="{{url('/materials/create_contact')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ $material->name }}" />
        </div>

        <div class="form-group">
            <label for="inputContact">Contacto</label>
            <input
                type="text" class="form-control"
                name="number" id="inputContact"
                placeholder="112" value="{{ $material->number }}"/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/materials')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
    </div>
@endif
@endsection

