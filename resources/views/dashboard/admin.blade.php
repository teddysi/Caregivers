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
            <h1>Utilizadores</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'admin']) }}">Administrador</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'healthcarepro']) }}">Profissional de Saúde</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'caregiver']) }}">Cuidador</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
			@if (count($users))
				<form class="form" method="POST" action="{{ route('users') }}">
					{{ csrf_field() }}
					<input name="dashboard" type="hidden" value="true">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputUserName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="userName" class="form-control" id="inputUserName" placeholder="Nome">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputEmail">Email</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="userEmail" class="form-control" id="inputEmail" placeholder="Email">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<div class="form-group form-inline">
								<label for="userRole">Função:</label>
								<select name="userRole" class="form-control">
									<option value="all">Todos</option>
									<option value="admin">Administrador</option>
									<option value="healthcarepro">Profissional de Saúde</option>
									<option value="caregiver">Cuidador</option>
								</select>
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
							<th>Função</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($users as $user)
							<tr>
					        	<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>{{ $user->role }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('users.show', ['user' => $user->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('users.edit', ['user' => $user->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{ route('users.toggleBlock', ['user' => $user->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													@if ($user->blocked == 0)
														<button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
													@elseif ($user->blocked == 1)
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
				<h4>Não existem utilizadores.</h4>
			@endif
			<div class="text-center">
				{!! $users->links() !!}
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
</div>	

@endsection

 