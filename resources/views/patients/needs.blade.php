@extends ('layouts.master')

@section('title', 'Lista de Necessidades')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<legend>Necessidades de {{ $patient->name }}</legend>
			@if (count($needs))
		        <table class="table table-striped patient-needs">
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
								<td style="width:25%">{{ $need->creator->username }}</td>
								<td style="width:45%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('needs.show', ['need' => $need->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $need->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $need->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('patients.diassociateNeed', ['patient' => $patient->id, 'need' => $need->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem necessidades associadas a este Paciente.</h4>
			@endif
			<div class="text-center">
				{!! $needs->links() !!}
			</div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<legend>Outras Necessidades</legend>
			@if (count($notMyNeeds))
		        <table class="table table-striped other-needs">
			        <thead>
			            <tr>
							<th>Descrição</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($notMyNeeds as $notMyNeed)
							<tr>
					        	<td>{{ $notMyNeed->description }}</td>
								<td style="width:25%">{{ $notMyNeed->creator->username }}</td>
								<td style="width:45%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('needs.show', ['need' => $notMyNeed->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $notMyNeed->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $notMyNeed->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('patients.associateNeed', ['patient' => $patient->id, 'need' => $notMyNeed->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem necessidades por associar a este Paciente.</h4>
			@endif
			<div class="text-center">
				{!! $notMyNeeds->links() !!}
			</div>
        </div>
	</div>
	<p><a class="btn btn-default" href="javascript:history.back()">Voltar Atrás</a></p>
</div>

@endsection