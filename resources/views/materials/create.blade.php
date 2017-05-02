@extends('layouts.master')

@section('tittle', 'Criar Material')

@section('content')

<div class="container">
    <form action="{{url('/materials/create')}}" method="POST" class="form-group">
        {{ csrf_field() }}
        <input name="type" type="hidden" value="{{ $type }}">

        <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text" class="form-control"
                name="name" id="inputName"
                placeholder="Name" value="{{ old('name') }}" />
        </div>

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ old('description') }}" />
        </div>

        @if ($type == 'textFile' || $type == 'image')
            <div class="form-group">
                <label for="inputPath">Localização</label>
                <input
                    type="text" class="form-control"
                    name="path" id="inputPath"
                    placeholder="Localização" value="{{ old('path') }}" />
            </div>
        @endif

        @if ($type == 'video')
            <div class="form-group">
                <label for="inputURL">URL</label>
                <input
                    type="text" class="form-control"
                    name="url" id="inputURL"
                    placeholder="URL" value="{{ old('url') }}" />
            </div>
        @endif

        @if ($type == 'emergencyContact')
            <div class="form-group">
                <label for="inputNumber">Número</label>
                <input
                    type="text" class="form-control"
                    name="number" id="inputNumber"
                    placeholder="Número" value="{{ old('number') }}" />
            </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Criar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection

