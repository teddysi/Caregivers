@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Pacientes</legend>
			<div class="row">
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('patients.create') }}">Novo Paciente</a>
				</div>
			</div>
			<br />
			@if (count($patients))
				<form class="form" method="POST" action="{{ route('patients') }}">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputPatientName">Nome</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientName" class="form-control" id="inputPatientName" placeholder="Nome" value="{{ $searchData['patientName'] }}">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputEmail">Email</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientEmail" class="form-control" id="inputEmail" placeholder="Email" value="{{ $searchData['patientEmail'] }}">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputLocation">Localização</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientLocation" class="form-control" id="inputLocation" placeholder="Localização" value="{{ $searchData['patientLocation'] }}">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6">
							<label class="sr-only" for="inputCreator">Criador</label>
							<div class="input-group">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								</div>
								<input type="text" name="patientCreator" class="form-control" id="inputCreator" placeholder="Criador"  value="{{ $searchData['patientCreator'] }}">
							</div>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline">
								<label for="searchOrder">Ordenar por:</label>
								<select name="patientSort" class="form-control">
									<option value="mrc" {{ $searchData['patientSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
									<option value="lrc" {{ $searchData['patientSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
									<option value="name_az" {{ $searchData['patientSort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
									<option value="name_za" {{ $searchData['patientSort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
									<option value="email_az" {{ $searchData['patientSort'] == 'email_az' ? 'selected' : '' }}>Email (A-Z)</option>
									<option value="email_za" {{ $searchData['patientSort'] == 'email_za' ? 'selected' : '' }}>Email (Z-A)</option>
									<option value="location_az" {{ $searchData['patientSort'] == 'location_az' ? 'selected' : '' }}>Localização (A-Z)</option>
									<option value="location_za" {{ $searchData['patientSort'] == 'location_za' ? 'selected' : '' }}>Localização (Z-A)</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline	">
								<label for="searchItemsPerPage">Nº pacientes por página:</label>
								<select name="patientPages" class="form-control">
									<option value="10" {{ $searchData['patientPages'] == '10' ? 'selected' : '' }}>10</option>
									<option value="20" {{ $searchData['patientPages'] == '20' ? 'selected' : '' }}>20</option>
									<option value="30" {{ $searchData['patientPages'] == '30' ? 'selected' : '' }}>30</option>
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
</div>	

@endsection