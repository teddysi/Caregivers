@extends ('layouts.master')

@section ('content')
@if(Session::has('blockedStatus'))
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-success"><em> {!! session('blockedStatus') !!}</em></div>
			</div>
		</div>
	</div>
@endif
<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Cuidadores</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'caregiver']) }}">Cuidador</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($caregivers))
				<form class="form" method="POST" action="{{ route('users') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputUserName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="userName" class="form-control" id="inputUserName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputEmail">Email</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="userEmail" class="form-control" id="inputEmail" placeholder="Email">
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
							<th>Email</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($caregivers as $caregiver)
							<tr>
					        	<td>{{ $caregiver->name }}</td>
								<td>{{ $caregiver->email }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('users.show', ['user' => $caregiver->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('users.edit', ['user' => $caregiver->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{ route('users.toggleBlock', ['user' => $caregiver->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													@if ($caregiver->blocked == 0)
														<button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
													@elseif ($caregiver->blocked == 1)
														<button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
													@endif
												</div>
											</form>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem cuidadores.</h4>
			@endif
			<div class="text-center">
				{!! $caregivers->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <h1>Pacientes</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('patients.create') }}">Paciente</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($patients))
				<form class="form" method="POST" action="{{ route('patients') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputPatientName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientName" class="form-control" id="inputPatientName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputPatientEmail">Email</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientEmail" class="form-control" id="inputPatientEmail" placeholder="Email">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputPatientLocation">Localização</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientLocation" class="form-control" id="inputPatientLocation" placeholder="Localização">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 pull-right">
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
							<th>Email</th>
							<th>Localização</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($patients as $patient)
							<tr>
					        	<td>{{ $patient->name }}</td>
								<td>{{ $patient->email }}</td>
								<td>{{ $patient->location }}</td>
								<td>{{ $patient->creator->username }}</td>
								<td style="width:37%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('patients.show', ['patient' => $patient->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('patients.needs', ['patient' => $patient->id]) }}">Necessidades</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('patients.edit', ['patient' => $patient->id]) }}">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem pacientes.</h4>
			@endif
			<div class="text-center">
				{!! $patients->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <h1>Necessidades</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('needs.create') }}">Necessidade</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($needs))
				<form class="form" method="POST" action="{{ route('needs') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputDescription">Descrição</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="needDescription" class="form-control" id="inputDescription" placeholder="Descrição">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="needCreator" class="form-control" id="inputCreator" placeholder="Criador">
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
							<th>Descrição</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($needs as $need)
							<tr>
					        	<td>{{ $need->description }}</td>
								<td>{{ $need->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.show', ['need' => $need->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $need->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $need->id]) }}">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem necessidades.</h4>
			@endif
			<div class="text-center">
				{!! $needs->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <h1>Materiais</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'text']) }}">Texto</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'image']) }}">Imagem</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'video']) }}">Video</a>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'emergencyContact']) }}">Contacto de Emergência</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'annex']) }}">Anexo</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'composite']) }}">Composto</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($materials))
				<form class="form" method="POST" action="{{ route('materials') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputMaterialName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="materialName" class="form-control" id="inputMaterialName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<div class="form-group form-inline">
								<label for="materialType">Tipo:</label>
								<select name="materialType" class="form-control">
									<option value="all">Todos</option>
									<option value="text">Texto</option>
									<option value="image">Imagem</option>
									<option value="video">Video</option>
									<option value="emergencyContact">Contacto de Emergência</option>
									<option value="annex">Anexo</option>
									<option value="composite">Composto</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="materialCreator" class="form-control" id="inputCreator" placeholder="Criador">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 pull-right">
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
							<th>Tipo</th>
			                <th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($materials as $material)
							<tr>
					        	<td>{{ $material->name }}</td>
								<td>{{ $material->type }}</td>
					        	<td>{{ $material->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('materials.show', ['material' => $material->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['material' => $material->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{ route('materials.toggleBlock', ['material' => $material->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													@if ($material->blocked == 0)
														<button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
													@elseif ($material->blocked == 1)
														<button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
													@endif
												</div>
											</form>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem materiais.</h4>
			@endif
			<div class="text-center">
				{!! $materials->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <h1>Questionários</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('quizs.create') }}">Questionário</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($quizs))
				<form class="form" method="POST" action="{{ route('quizs') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuizName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="quizName" class="form-control" id="inputQuizName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuizCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="quizCreator" class="form-control" id="inputQuizCreator" placeholder="Criador">
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
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('quizs.show', ['quiz' => $quiz->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('quizs.edit', ['quiz' => $quiz->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{route('quizs.delete', ['quiz' => $quiz->id])}}" method="POST" class="form-group">
												{{ method_field('DELETE') }}
										        {{ csrf_field() }}
												<button type="submit" class="btn btn-block btn-danger" name="save">Eliminar</button>
										    </form>
										</div>
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
	<div class="row">
		<div class="col-lg-12">
            <h1>Questões</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('questions.create') }}">Questão</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($questions))
				<form class="form" method="POST" action="{{ route('questions') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuestion">Questão</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="question" class="form-control" id="inputQuestion" placeholder="Pergunta">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<label class="sr-only" for="inputQuestionCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="questionCreator" class="form-control" id="inputQuestionCreator" placeholder="Criador">
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
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('questions.show', ['question' => $question->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('questions.edit', ['question' => $question->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{route('questions.delete', ['question' => $question->id])}}" method="POST" class="form-group">
												{{ method_field('DELETE') }}
										        {{ csrf_field() }}
												<button type="submit" class="btn btn-block btn-danger" name="save">Eliminar</button>
										    </form>
										</div>
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

 