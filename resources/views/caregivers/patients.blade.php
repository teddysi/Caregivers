@extends ('layouts.master')

@section('title', 'Lista de Pacientes')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1>Pacientes de {{ $caregiver->name }}</h1>
			@if (count($patients))
				<br /><br />
				<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Email</th>
							<th>Localização</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($patients as $patient)
							<tr>
					        	<td>{{ $patient->name }}</td>
								<td>{{ $patient->email }}</td>
                                <td>{{ $patient->location }}</td>
								<td style="width:50%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('patients.show', ['patient' => $patient->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('patients.needs', ['patient' => $patient->id]) }}">Necessidades</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('patients.edit', ['patient' => $patient->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('caregivers.diassociatePatient', ['caregiver' => $caregiver->id, 'patient' => $patient->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem pacientes.</h4>
			@endif
			<div class="text-center">
				{!! $patients->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<h1>Pacientes sem cuidador</h1>
			@if (count($notMyPatients))
				<br /><br />
				<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Email</th>
							<th>Localização</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($notMyPatients as $patient)
							<tr>
					        	<td>{{ $patient->name }}</td>
								<td>{{ $patient->email }}</td>
                                <td>{{ $patient->location }}</td>
								<td style="width:50%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('patients.show', ['patient' => $patient->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('patients.needs', ['patient' => $patient->id]) }}">Necessidades</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('patients.edit', ['patient' => $patient->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('caregivers.associatePatient', ['caregiver' => $caregiver->id, 'patient' => $patient->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem pacientes.</h4>
			@endif
			<div class="text-center">
				{!! $notMyPatients->links() !!}
			</div>
        </div>
	</div>
	<p><a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a></p>
</div>

@endsection