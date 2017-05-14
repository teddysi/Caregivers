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
            <h1>Materiais</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'text']) }}">Texto</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'image']) }}">Imagem</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'video']) }}">Video</a>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'emergencyContact']) }}">Contacto de Emergência</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'annex']) }}">Anexo</a>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'composite']) }}">Composto</a>
					</div>
				</div>
			</fieldset>
			<br /><br />
			<legend>Listar</legend>
            <form class="form" method="POST" action="{{ route('materials') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputMaterialName">Nome</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</div>
							<input type="text" name="materialName" class="form-control" id="inputMaterialName" placeholder="Nome" value="{{ $searchData['materialName'] }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline	">
							<label for="materialType">Tipo:</label>
							<select name="materialType" class="form-control">
                                <option value="all" {{ $searchData['materialType'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="text" {{ $searchData['materialType'] == 'text' ? 'selected' : '' }}>Texto</option>
					    		<option value="image" {{ $searchData['materialType'] == 'image' ? 'selected' : '' }}>Imagem</option>
								<option value="video" {{ $searchData['materialType'] == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="emergencyContact" {{ $searchData['materialType'] == 'emergencyContact' ? 'selected' : '' }}>Contacto de Emergência</option>
								<option value="annex" {{ $searchData['materialType'] == 'annex' ? 'selected' : '' }}>Anexo</option>
								<option value="composite" {{ $searchData['materialType'] == 'composite' ? 'selected' : '' }}>Composto</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputCreator">Criador</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<input type="text" name="materialCreator" class="form-control" id="inputCreator" placeholder="Criador"  value="{{ $searchData['materialCreator'] }}">
						</div>
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="materialSort" class="form-control">
								<option value="mrc" {{ $searchData['materialSort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['materialSort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="name_az" {{ $searchData['materialSort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
								<option value="name_za" {{ $searchData['materialSort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
								<option value="type_az" {{ $searchData['materialSort'] == 'type_az' ? 'selected' : '' }}>Tipo (A-Z)</option>
								<option value="type_za" {{ $searchData['materialSort'] == 'type_za' ? 'selected' : '' }}>Tipo (Z-A)</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº materiais por página:</label>
							<select name="materialPages" class="form-control">
								<option value="10" {{ $searchData['materialPages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['materialPages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['materialPages'] == '30' ? 'selected' : '' }}>30</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="blockedItems">Materiais:</label>
							<select name="materialBlocked" class="form-control">
								<option value="all" {{ $searchData['materialBlocked'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="just_blocked" {{ $searchData['materialBlocked'] == 'just_blocked' ? 'selected' : '' }}>Bloqueados</option>
								<option value="just_unblocked" {{ $searchData['materialBlocked'] == 'just_unblocked' ? 'selected' : '' }}>Não Bloqueados</option>
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
            @if (count($materials))
                <br />
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