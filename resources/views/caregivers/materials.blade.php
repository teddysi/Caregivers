@extends ('layouts.master')

@section('title', 'Lista de Materiais')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<legend>Associar Materiais</legend>
			<form class="form" method="POST" action="{{ route('caregivers.associateMaterial', ['caregiver' => $caregiver->id]) }}">
				{{ csrf_field() }}
                <div class="row">
					@if (count($patientsNeeds))
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline">
								<label for="need">Necessidade:</label>
								<select name="need" class="form-control">
									@foreach ($patientsNeeds as $patientsNeed)
										<option value="{{ $patientsNeed->id }}">{{ $patientsNeed->description }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6">
							<div class="form-group form-inline">
								<label for="material">Material:</label>
								<select name="material" class="form-control">
									@foreach ($allMaterials as $material)
										<option value="{{ $material->id }}">{{ $material->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 pull-right">
							<button type="submit" class="btn btn-default btn-block">Associar</button>
						</div>
					@else
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group form-inline">
								<label for="material">Material:</label>
								<select name="material" class="form-control">
									@foreach ($allMaterials as $material)
										<option value="{{ $material->id }}">{{ $material->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 pull-right">
							<button type="submit" class="btn btn-default btn-block">Associar</button>
						</div>
					@endif
				</div>
			</form>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-12">
			<legend>Necessidades dos Pacientes de {{ $caregiver->name }}</legend>
			@if (count($patientsNeeds))
		        <table class="table table-striped patients-needs">
			        <thead>
			            <tr>
							<th>Descrição</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($patientsNeeds as $patientsNeed)
							<tr>
					        	<td>{{ $patientsNeed->description }}</td>
								<td>{{ $patientsNeed->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.show', ['need' => $patientsNeed->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $patientsNeed->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $patientsNeed->id]) }}">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Os pacientes deste Cuidador não têm necessidades.</h4>
			@endif
        </div>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-12">
			<legend>Materiais de {{ $caregiver->name }}</legend>
			@if (count($materials))
		        <table class="table table-striped caregiver-materials">
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
                                <td style="width:55%">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 col-lg-3">
                                            <a class="btn btn-block btn-primary" href="{{ route('materials.show', ['id' => $material->id]) }}">Detalhes</a>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg-3">
                                            <a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['id' => $material->id]) }}">Editar</a>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg-3	">
                                                <a class="btn btn-block btn-primary" href="{{ route('materials.rate', ['caregiver' => $caregiver->id, 'material' => $material->id]) }}">Avaliações</a>
                                        </div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('caregivers.diassociateMaterial', ['caregiver' => $caregiver->id, 'material' => $material->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem materiais associados a este Cuidador.</h4>
			@endif
			<div class="text-center">
				{!! $materials->links() !!}
			</div>
        </div>
	</div>
	<br />
	<p><a class="btn btn-default" href="javascript:history.back()">Voltar Atrás</a></p>
</div>

@endsection