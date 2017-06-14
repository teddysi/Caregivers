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
                    @if($question->canBeEditedOrBlocked)
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-warning" href="{{ route('questions.edit', ['question' => $question->id]) }}">Editar</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <form action="{{ route('questions.toggleBlock', ['question' => $question->id]) }}" method="POST" class="form-group">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        @if ($question->blocked == 0)
                                            <button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
                                        @elseif ($question->blocked == 1)
                                            <button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar a atrás</a>
                        </div>
                    </div>
                </div>
            </div>
 		</div>
	</div>
    @if($question->type == 'radio')
        <br />
        <div class="row">
            <div class="col-lg-12">
                <legend>Opções de resposta</legend>
                <div>
                    @foreach($values as $value)
                        <h5>{{ $value }};</h5>
                    @endforeach
                </div>
            </div>
	    </div>
    @endif
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Registros</legend>
			@if (count($logs))
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Tarefa</th>
							<th>Realizada por</th>
                            <th>Data</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach($logs as $log)
							<tr>
					        	<td>{{$log->performed_task}}</td>
								<td>{{$log->doneBy->username}}</td>
                                <td>{{$log->created_at}}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem registros referentes a esta Questão.</h4>
			@endif
 		</div>
	</div>
</div>
@endsection