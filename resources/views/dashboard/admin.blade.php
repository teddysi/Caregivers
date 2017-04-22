@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-6 col-sm-6 col-md-6">
			<a class="btn btn-primary" href="{{url('/all_users')}}">Todos os utilizadores</a>
		</div>
		<div class="col-6 col-md-6">
			<a class="btn btn-primary" href="{{url('/materials')}}">Materiais</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <h1>Todos os Materiais</h1>
			@if (count($materials))
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
										<div class="col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('materials.show', ['id' => $material->id]) }}">Detalhes</a>
										</div>
										<div class="col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['id' => $material->id]) }}">Editar</a>
										</div>
										<div class="col-lg-4">
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

 