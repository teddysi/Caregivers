@extends('layouts.master')

@section('tittle', 'Create need')

@section('content')

<div class="container">
    <form action="{{url('/needs/save_need')}}" method="post" class="form-group">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="inputDescription">Descrição</label>
            <input
                type="text" class="form-control"
                name="description" id="inputDescription"
                placeholder="Descrição" value="{{ $need->description }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Adicionar</button>
            <a class="btn btn-default" href="{{url('/needs')}}">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>
@endsection
