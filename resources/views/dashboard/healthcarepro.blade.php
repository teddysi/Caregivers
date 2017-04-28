@extends ('layouts.master')


@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Cuidadores</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'caregiver']) }}">Cuidador</a>
				</div>
			</fieldset>
			@if (count($caregivers))
				<br /><br />
				<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Email</th>
							<th>Profissionais de Saúde</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($caregivers as $caregiver)
							<tr>
					        	<td>{{ $caregiver->name }}</td>
								<td>{{ $caregiver->email }}</td>
								<td>{{ count($caregiver->healthcarePros) }}/2</td>
								<td style="width:50%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-xs btn-block btn-primary" href="{{ route('users.show', ['user' => $caregiver->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-xs btn-block btn-primary" href="{{ route('caregivers.patients', ['caregiver' => $caregiver->id]) }}">Pacientes</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-xs btn-block btn-primary" href="{{ route('caregivers.materials', ['caregiver' => $caregiver->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-xs btn-block btn-warning" href="{{ route('users.edit', ['user' => $caregiver->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('users.toggleBlock', ['users' => $caregiver->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													@if ($caregiver->blocked == 0)
														<button type="submit" class="btn btn-xs btn-block btn-danger" name="block">Bloquear</button>
													@elseif ($caregiver->blocked == 1)
														<button type="submit" class="btn btn-xs btn-block btn-success" name="unblock">Desbloquear</button>
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
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('patients.create') }}">Paciente</a>
				</div>
			</fieldset>
			@if (count($patients))
				<br /><br />
				<legend>Listar</legend>
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
								<td style="width:35%">
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
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('needs.create') }}">Necessidade</a>
				</div>
			</fieldset>
			@if (count($needs))
				<br /><br />
				<legend>Listar</legend>
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
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'textFile']) }}">Ficheiro de Texto</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'image']) }}">Imagem</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'video']) }}">Video</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'emergencyContact']) }}">Contacto de Emergência</a>
				</div>
			</fieldset>
			@if (count($materials))
				<br /><br />
				<legend>Listar</legend>
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
								<input type="text" name="name" class="form-control" id="inputMaterialName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<div class="form-group form-inline">
								<label for="materialType">Tipo:</label>
								<select name="type" class="form-control">
									<option value="all">Todos</option>
									<option value="textFile">Ficheiro de Texto</option>
									<option value="image">Imagem</option>
									<option value="video">Video</option>
									<option value="emergencyContact">Contacto de Emergência</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="creator" class="form-control" id="inputCreator" placeholder="Criador">
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
</div>
	

@endsection

 