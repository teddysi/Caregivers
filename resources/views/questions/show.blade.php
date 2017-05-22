@extends ('layouts.master')

@section('title', 'Pergunta')

@section ('content')

<div class="container">
    <h2><strong>Questão:</strong> {{ $question->question }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Criador:</strong> {{ $question->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $question->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $question->updated_at }}</h4>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('questions.edit', ['question' => $question->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <form action="{{route('questions.delete', ['question' => $question->id])}}" method="POST" class="form-group">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-block btn-danger" name="save">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar a atrás</a>
                        </div>
                    </div>
                </div>
            </div>
 		</div>
	</div>
</div>
@endsection