@extends ('layouts.master')

@section ('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Questões</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary create_question_button" href="{{ route('questions.create') }}">Questão</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			<form class="form" method="POST" action="{{ route('questions') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputQuestion">Questão</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</div>
							<input type="text" name="question" class="form-control" id="inputQuestion" placeholder="Pergunta" value="{{ $searchData['question'] }}">
						</div>
					</div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputQuestionCreator">Criador</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<input type="text" name="questionCreator" class="form-control" id="inputQuestionCreator" placeholder="Criador" value="{{ $searchData['questionCreator'] }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline	">
							<label for="blockedItems">Questões:</label>
							<select name="questionBlocked" class="form-control">
								<option value="all" {{ $searchData['questionBlocked'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="just_blocked" {{ $searchData['questionBlocked'] == 'just_blocked' ? 'selected' : '' }}>Bloqueados</option>
								<option value="just_unblocked" {{ $searchData['questionBlocked'] == 'just_unblocked' ? 'selected' : '' }}>Não Bloqueados</option>
							</select>
						</div>
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="questionSort" class="form-control">
								<option value="mrc" {{ $searchData['questionSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['questionSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="question_az" {{ $searchData['questionSort'] == 'question_az' ? 'selected' : '' }}>Questão (A-Z)</option>
								<option value="question_za" {{ $searchData['questionSort'] == 'question_za' ? 'selected' : '' }}>Questão (Z-A)</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº questões por página:</label>
							<select name="questionPages" class="form-control">
								<option value="10" {{ $searchData['questionPages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['questionPages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['questionPages'] == '30' ? 'selected' : '' }}>30</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 pull-right">
						<button type="submit" class="btn btn-default btn-block">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Procurar
						</button>
					</div>
				</div>
			</form>
            @if (count($questions))
                <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Questão</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($questions as $question)
							<tr>
					        	<td>{{ $question->question }}</td>
								<td>{{ $question->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										@if ($question->canBeEditedOrBlocked)
											<div class="col-sm-6 col-md-4 col-lg-4">
												<a class="btn btn-block btn-primary" href="{{route('questions.show', ['question' => $question->id])}}">Detalhes</a>
											</div>
											<div class="col-sm-6 col-md-4 col-lg-4">
												<a class="btn btn-block btn-warning" href="{{route('questions.edit', ['question' => $question->id])}}">Editar</a>
											</div>										
											<div class="col-sm-6 col-md-4 col-lg-4">
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
										@else
											<div class="col-sm-12 col-md-12 col-lg-12">
												<a class="btn btn-block btn-primary" href="{{route('questions.show', ['question' => $question->id])}}">Detalhes</a>
											</div>
										@endif
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem questões.</h4>
			@endif
			<div class="text-center">
				{!! $questions->links() !!}
			</div>
        </div>
	</div>
</div>	

@endsection