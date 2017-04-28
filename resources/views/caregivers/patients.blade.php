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
								<td>
									<form action="{{ route('caregivers.diassociatePatient', ['caregiver' => $caregiver->id, 'patient' => $patient->id]) }}" method="POST" class="form-group">
										{{ csrf_field() }}
										<div class="form-group">
											<button type="submit" class="btn btn-block btn-danger" name="diassociate">Diassociate</button>
										</div>
									</form>
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
								<td>
									<form action="{{ route('caregivers.associatePatient', ['caregiver' => $caregiver->id, 'patient' => $patient->id]) }}" method="POST" class="form-group">
										{{ csrf_field() }}
										<div class="form-group">
											<button type="submit" class="btn btn-block btn-success" name="associate">Associate</button>
										</div>
									</form>
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
</div>

@endsection