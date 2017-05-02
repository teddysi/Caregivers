@extends ('layouts.master')

@section('title', 'Lista de Materiais')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1>Materiais de {{ $need->description }}</h1>
			@if (count($materials))
				<br /><br />
				<legend>Listar</legend>
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
								<td style="width:45%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{{ route('materials.show', ['material' => $material->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['material' => $material->id]) }}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
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
										<div class="col-sm-6 col-md-3 col-lg-3">
											<form action="{{ route('needs.diassociateMaterial', ['need' => $need->id, 'material' => $material->id]) }}" method="POST" class="form-group">
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
				<h4>Não existem materiais.</h4>
			@endif
			<div class="text-center">
				{!! $materials->links() !!}
			</div>
			<p><a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a></p>
        </div>
	</div>
</div>

@endsection