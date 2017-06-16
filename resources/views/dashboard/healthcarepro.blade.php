@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Os meus Cuidadores</legend>
            @if (count($caregivers))
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
								<td style="width:70%">
									<div class="row">
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-block btn-primary" href="{{ route('users.show', ['user' => $caregiver->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-block btn-primary" href="{{ route('caregivers.patients', ['caregiver' => $caregiver->id]) }}">Pacientes</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-block btn-primary" href="{{ route('caregivers.materials', ['caregiver' => $caregiver->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
											<a class="btn btn-block btn-warning {{ 'user_update_'.$caregiver->id.'_button' }}" href="{{ route('users.edit', ['user' => $caregiver->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-2 col-lg-2">
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
                                        <div class="col-sm-6 col-md-2 col-lg-2">
											<form action="{{ route('users.diassociateCaregiver', ['user' => Auth::user()->id, 'caregiver' => $caregiver->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													<button type="submit" class="btn btn-block btn-danger" name="diassociate">Desassociar</button>
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
				<h4>Não existem cuidadores a meu cargo.</h4>
			@endif
			<div class="text-center">
				{!! $caregivers->links() !!}
			</div>
        </div>
	</div>
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Outros cuidadores que necessitam de Profissionais de Saúde</legend>
            @if (count($otherCaregivers))
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
                        @foreach ($otherCaregivers as $otherCaregiver)
							<tr>
					        	<td>{{ $otherCaregiver->name }}</td>
								<td>{{ $otherCaregiver->email }}</td>
								<td style="width:70%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('users.show', ['user' => $otherCaregiver->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('users.edit', ['user' => $otherCaregiver->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('users.toggleBlock', ['user' => $otherCaregiver->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													@if ($otherCaregiver->blocked == 0)
														<button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
													@elseif ($otherCaregiver->blocked == 1)
														<button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
													@endif
												</div>
											</form>
										</div>
                                        <div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('users.associateCaregiver', ['user' => Auth::user()->id, 'caregiver' => $otherCaregiver->id]) }}" method="POST" class="form-group">
												{{ csrf_field() }}
												<div class="form-group">
													<button type="submit" class="btn btn-block btn-success" name="associate">Associar</button>
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
				<h4>Não existem cuidadores que necessitem de Profissionais de Saúde.</h4>
			@endif
			<div class="text-center">
				{!! $otherCaregivers->links() !!}
			</div>
        </div>
	</div>
</div>	

@endsection