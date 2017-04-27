@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Utilizadores</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'admin']) }}">Administrador</a>
				</div>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'healthcarepro']) }}">Profissional de Saúde</a>
				</div>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'caregiver']) }}">Cuidador</a>
				</div>
			</fieldset>
			<br /><br />
			<legend>Listar</legend>
            <form class="form" method="POST" action="{{ route('users') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputUserName">Nome</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</div>
							<input type="text" name="name" class="form-control" id="inputUserName" placeholder="Nome" value="{{ $searchData['name'] }}">
						</div>
					</div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputEmail">Email</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<input type="text" name="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ $searchData['email'] }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline">
							<label for="userRole">Função:</label>
							<select name="role" class="form-control">
                                <option value="all" {{ $searchData['role'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="admin" {{ $searchData['role'] == 'admin' ? 'selected' : '' }}>Administrador</option>
					    		<option value="healthcarepro" {{ $searchData['role'] == 'healthcarepro' ? 'selected' : '' }}>Profissional de Saúde</option>
								<option value="caregiver" {{ $searchData['role'] == 'caregiver' ? 'selected' : '' }}>Cuidador</option>
                            </select>
						</div>
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="sort" class="form-control">
								<option value="mrc" {{ $searchData['sort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['sort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="name_az" {{ $searchData['sort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
								<option value="name_za" {{ $searchData['sort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
								<option value="email_az" {{ $searchData['sort'] == 'email_az' ? 'selected' : '' }}>Email (A-Z)</option>
								<option value="email_za" {{ $searchData['sort'] == 'email_za' ? 'selected' : '' }}>Email (Z-A)</option>
                                <option value="role_az" {{ $searchData['sort'] == 'role_az' ? 'selected' : '' }}>Função (A-Z)</option>
								<option value="role_za" {{ $searchData['sort'] == 'role_za' ? 'selected' : '' }}>Função (Z-A)</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº utilizadores por página:</label>
							<select name="pages" class="form-control">
								<option value="10" {{ $searchData['pages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['pages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['pages'] == '30' ? 'selected' : '' }}>30</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="blockedItems">Utilizadores:</label>
							<select name="blocked" class="form-control">
								<option value="all" {{ $searchData['blocked'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="just_blocked" {{ $searchData['blocked'] == 'just_blocked' ? 'selected' : '' }}>Bloqueados</option>
								<option value="just_unblocked" {{ $searchData['blocked'] == 'just_unblocked' ? 'just_unblocked' : '' }}>Não Bloqueados</option>
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
            @if (count($users))
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
											<form action="{{ route('users.toggleBlock', ['users' => $user->id]) }}" method="POST" class="form-group">
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
</div>	

@endsection