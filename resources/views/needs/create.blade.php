@extends('layouts.master')

@section('tittle', 'Criar Necessidade')

@section('content')
<div class="container">
    <legend>Nova Necessidade</legend>
    <form action="{{url('/needs/create')}}" method="POST" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ old('description') }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Criar</button>
            <a class="btn btn-default" href="{{ route('needs') }}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection