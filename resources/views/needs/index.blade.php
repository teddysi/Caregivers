@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Necessidades</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('needs.create') }}">Necessidade</a>
					</div>
				</div>
			</fieldset>
			<br />
			<legend>Listar</legend>
            <form class="form" method="POST" action="{{ route('needs') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<label class="sr-only" for="inputDescription">Descrição</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</div>
							<input type="text" name="needDescription" class="form-control" id="inputDescription" placeholder="Descrição" value="{{ $searchData['needDescription'] }}">
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<label class="sr-only" for="inputCreator">Criador</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<input type="text" name="needCreator" class="form-control" id="inputCreator" placeholder="Criador" value="{{ $searchData['needCreator'] }}">
						</div>
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="needSort" class="form-control">
								<option value="mrc" {{ $searchData['needSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['needSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="description_az" {{ $searchData['needSort'] == 'description_az' ? 'selected' : '' }}>Descrição (A-Z)</option>
								<option value="description_za" {{ $searchData['needSort'] == 'description_za' ? 'selected' : '' }}>Descrição (Z-A)</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº necessidades por página:</label>
							<select name="needPages" class="form-control">
								<option value="10" {{ $searchData['needPages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['needPages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['needPages'] == '30' ? 'selected' : '' }}>30</option>
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
            @if (count($needs))
                <br />
                <table class="table table-striped">
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
								<td>{{ $need->creator->username }}</td>
								<td style="width:35%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.show', ['need' => $need->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $need->id]) }}">Materiais</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $need->id]) }}">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem necessidades.</h4>
			@endif
			<div class="text-center">
				{!! $needs->links() !!}
			</div>
        </div>
	</div>
</div>	

@endsection