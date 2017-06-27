@extends ('layouts.master')

@section('title', 'Questionário')

@section ('content')

<div class="container">
    <h2><strong>Questionário:</strong> {{ $quiz->name }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Criador:</strong> {{ $quiz->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $quiz->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $quiz->updated_at }}</h4>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    @if ($quiz->canBeEditedOrBlocked)
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-warning" href="{{ route('quizs.edit', ['quiz' => $quiz->id]) }}">Editar</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <form action="{{ route('quizs.toggleBlock', ['quiz' => $quiz->id]) }}" method="POST" class="form-group">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        @if ($quiz->blocked == 0)
                                            <button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
                                        @elseif ($quiz->blocked == 1)
                                            <button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-primary" href="{{ route('quizs.questions', ['quiz' => $quiz->id]) }}">Questões</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
 		</div>
	</div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <legend>Questões Associadas</legend>
            @if (count($quizQuestions))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ordem</th>
                            <th>Questão</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizQuestions as $index => $quizQuestion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $quizQuestion->question }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h4>Não existem questões associadas a este Questionário.</h4>
            @endif
            <div class="text-center">
                {!! $quizQuestions->links() !!}
            </div>
        </div>
    </div>
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Registos</legend>
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
				<h4>Não existem registos referentes a este Questionário.</h4>
			@endif
 		</div>
	</div>
</div>
@endsection