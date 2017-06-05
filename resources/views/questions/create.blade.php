@extends('layouts.master')

@section('tittle', 'Perguntas')

@section('content')

<div class="container">
    <form action="{{route('questions.create')}}" method="POST" class="form-group">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="inputQuestion">Nome</label>
            <input
                type="text" class="form-control"
                name="question" id="inputQuestion"
                placeholder="Pergunta?" value="{{ old('question') }}" />
        </div>

        <div class="form-group">
                <label for="selectType">Tipo de Resposta</label>
                <select name="selectType" id="selectType" class="form-control selectpicker" onchange="selectTypeChange()">
                    <option value="text" {{ old('selectType') == 'text' ? 'selected' : '' }}>Texto</option>
                    <option value="radio" {{ old('selectType') == 'radio' ? 'selected' : '' }}>Opções</option>
                </select>
            </div>
            <div class="form-group" id="inputOptions" style="display:none">
                <label for="inputOptions">Opções de Resposta</label>
                <h5>Cada opção deve ser separa e terminada por ";". Exemplo: "Gosto muito;Não gosto;Sim;Não;"</h5>
                <input type="text" class="form-control" name="values" value="{{ old('values') }}"/>
            </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
            <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
        </div>
    @include('layouts.errors')
    </form>
</div>

@endsection

@section('custom_js')
    <script src="{{ asset('js/question_type_select.js') }}"></script>
@endsection 

