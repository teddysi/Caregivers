@extends ('layouts.master')

@section ('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Questionários</legend>
			<div class="row">
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('quizs.create') }}">Novo Questionário</a>
				</div>
			</div>
			<br />
			@if (count($quizs))
				<form class="form" method="POST" action="{{ route('quizs') }}">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuizName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="quizName" class="form-control" id="inputQuizName" placeholder="Nome" value="{{ $searchData['quizName'] }}">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuizCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="quizCreator" class="form-control" id="inputQuizCreator" placeholder="Criador" value="{{ $searchData['quizCreator'] }}">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline	">
								<label for="blockedItems">Questionários:</label>
								<select name="quizBlocked" class="form-control">
									<option value="all" {{ $searchData['quizBlocked'] == 'all' ? 'selected' : '' }}>Todos</option>
									<option value="just_blocked" {{ $searchData['quizBlocked'] == 'just_blocked' ? 'selected' : '' }}>Bloqueados</option>
									<option value="just_unblocked" {{ $searchData['quizBlocked'] == 'just_unblocked' ? 'selected' : '' }}>Não Bloqueados</option>
								</select>
							</div>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline">
								<label for="searchOrder">Ordenar por:</label>
								<select name="quizSort" class="form-control">
									<option value="mrc" {{ $searchData['quizSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
									<option value="lrc" {{ $searchData['quizSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
									<option value="name_az" {{ $searchData['quizSort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
									<option value="name_za" {{ $searchData['quizSort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline	">
								<label for="searchItemsPerPage">Nº questionários por página:</label>
								<select name="quizPages" class="form-control">
									<option value="10" {{ $searchData['quizPages'] == '10' ? 'selected' : '' }}>10</option>
									<option value="20" {{ $searchData['quizPages'] == '20' ? 'selected' : '' }}>20</option>
									<option value="30" {{ $searchData['quizPages'] == '30' ? 'selected' : '' }}>30</option>
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
				<br />
                <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($quizs as $quiz)
							<tr>
					        	<td>{{ $quiz->name }}</td>
								<td>{{ $quiz->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										@if ($quiz->canBeEditedOrBlocked)
											<div class="col-sm-6 col-md-4 col-lg-4">
												<a class="btn btn-block btn-primary" href="{{route('quizs.show', ['quiz' => $quiz->id])}}">Detalhes</a>
											</div>
											<div class="col-sm-6 col-md-4 col-lg-4">
												<a class="btn btn-block btn-warning" href="{{route('quizs.edit', ['quiz' => $quiz->id])}}">Editar</a>
											</div>
											<div class="col-sm-6 col-md-4 col-lg-4">
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
										@else
											<div class="col-sm-12 col-md-12 col-lg-12">
												<a class="btn btn-block btn-primary" href="{{route('quizs.show', ['quiz' => $quiz->id])}}">Detalhes</a>
											</div>
										@endif
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem questionários.</h4>
			@endif
			<div class="text-center">
				{!! $quizs->links() !!}
			</div>
        </div>
	</div>
</div>	

@endsection