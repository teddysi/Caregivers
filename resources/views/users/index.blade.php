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
			@if (Auth::user()->role == 'admin')
            	<h1>Utilizadores</h1>
			@elseif (Auth::user()->role == 'healthcarepro')
				<h1>Cuidadores</h1>
			@endif
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					@if (Auth::user()->role == 'admin')
						<div class="col-4 col-sm-4 col-md-4">
							<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'admin']) }}">Administrador</a>
						</div>
						<div class="col-4 col-sm-4 col-md-4">
							<a class="btn btn-block btn-primary" href="{{ route('users.create', ['role' =>'healthcarepro']) }}">Profissional de Saúde</a>
						</div>
					@endif
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary create_caregiver" href="{{ route('users.create', ['role' =>'caregiver']) }}">Cuidador</a>
					</div>
				</div>
			</fieldset>
			<br />
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
							<input type="text" name="userName" class="form-control" id="inputUserName" placeholder="Nome" value="{{ $searchData['userName'] }}">
						</div>
					</div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputEmail">Email</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</div>
							<input type="text" name="userEmail" class="form-control" id="inputEmail" placeholder="Email" value="{{ $searchData['userEmail'] }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						@if (Auth::user()->role == 'admin')
							<div class="form-group form-inline">
								<label for="userRole">Função:</label>
								<select name="userRole" class="form-control">
									<option value="all" {{ $searchData['userRole'] == 'all' ? 'selected' : '' }}>Todos</option>
									<option value="admin" {{ $searchData['userRole'] == 'admin' ? 'selected' : '' }}>Administrador</option>
									<option value="healthcarepro" {{ $searchData['userRole'] == 'healthcarepro' ? 'selected' : '' }}>Profissional de Saúde</option>
									<option value="caregiver" {{ $searchData['userRole'] == 'caregiver' ? 'selected' : '' }}>Cuidador</option>
								</select>
							</div>
						@elseif (Auth::user()->role == 'healthcarepro')
							<div class="form-group form-inline">
								<label for="userCaregivers">Cuidadores:</label>
								<select name="userCaregivers" class="form-control">
									<option value="all" {{ $searchData['userCaregivers'] == 'all' ? 'selected' : '' }}>Todos</option>
									<option value="mine" {{ $searchData['userCaregivers'] == 'mine' ? 'selected' : '' }}>Só os meus</option>
								</select>
							</div>
						@endif
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="userSort" class="form-control">
								<option value="mrc" {{ $searchData['userSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['userSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="name_az" {{ $searchData['userSort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
								<option value="name_za" {{ $searchData['userSort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
								<option value="email_az" {{ $searchData['userSort'] == 'email_az' ? 'selected' : '' }}>Email (A-Z)</option>
								<option value="email_za" {{ $searchData['userSort'] == 'email_za' ? 'selected' : '' }}>Email (Z-A)</option>
                                @if (Auth::user()->role != 'healthcarepro')
									<option value="role_az" {{ $searchData['userSort'] == 'role_az' ? 'selected' : '' }}>Função (A-Z)</option>
									<option value="role_za" {{ $searchData['userSort'] == 'role_za' ? 'selected' : '' }}>Função (Z-A)</option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº utilizadores por página:</label>
							<select name="userPages" class="form-control">
								<option value="10" {{ $searchData['userPages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['userPages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['userPages'] == '30' ? 'selected' : '' }}>30</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="blockedItems">Utilizadores:</label>
							<select name="userBlocked" class="form-control">
								<option value="all" {{ $searchData['userBlocked'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="just_blocked" {{ $searchData['userBlocked'] == 'just_blocked' ? 'selected' : '' }}>Bloqueados</option>
								<option value="just_unblocked" {{ $searchData['userBlocked'] == 'just_unblocked' ? 'selected' : '' }}>Não Bloqueados</option>
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
                <table class="table table-striped users_table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
							@if (Auth::user()->role != 'healthcarepro')
                            	<th>Função</th>
							@endif
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
							<tr>
					        	<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								@if (Auth::user()->role != 'healthcarepro')
									<td>{{ $user->role }}</td>
								@endif
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary {{'user_details_'.$user->id.'_button'}}" href="{{ route('users.show', ['user' => $user->id]) }}">Detalhes</a>
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
</div>	

@endsection