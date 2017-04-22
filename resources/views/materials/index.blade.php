@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Materiais</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'textFile']) }}">Ficheiro de Texto</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'image']) }}">Imagem</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'video']) }}">Video</a>
				</div>
				<div class="col-3 col-sm-3 col-md-3">
					<a class="btn btn-block btn-primary" href="{{ route('materials.create', ['type' =>'emergencyContact']) }}">Contacto de Emergência</a>
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
							<input type="text" name="name" class="form-control" id="inputMaterialName" placeholder="Nome" value="{{ $searchData['name'] }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="form-group form-inline	">
							<label for="materialType">Tipo:</label>
							<select name="type" class="form-control">
                                <option value="all" {{ $searchData['type'] == 'all' ? 'selected' : '' }}>Todos</option>
								<option value="textFile" {{ $searchData['type'] == 'textFile' ? 'selected' : '' }}>Ficheiro de Texto</option>
					    		<option value="image" {{ $searchData['type'] == 'image' ? 'selected' : '' }}>Imagem</option>
								<option value="video" {{ $searchData['type'] == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="emergencyContact" {{ $searchData['type'] == 'emergencyContact' ? 'selected' : '' }}>Contacto de Emergência</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6">
						<label class="sr-only" for="inputCreator">Criador</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
							</div>
							<input type="text" name="creator" class="form-control" id="inputCreator" placeholder="Criador"  value="{{ $searchData['creator'] }}">
						</div>
					</div>
				</div>
                <br />
                <div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline">
							<label for="searchOrder">Ordenar por:</label>
							<select name="sort" class="form-control">
								<option value="mrc" {{ $searchData['sort'] == 'mrc' ? 'selected' : '' }}>Mais Recentes</option>
								<option value="lrc" {{ $searchData['sort'] == 'lrc' ? 'selected' : '' }}>Menos Recentes</option>
								<option value="name_az" {{ $searchData['sort'] == 'name_az' ? 'selected' : '' }}>Nome (A-Z)</option>
								<option value="name_za" {{ $searchData['sort'] == 'name_za' ? 'selected' : '' }}>Nome (Z-A)</option>
								<option value="type_az" {{ $searchData['sort'] == 'type_az' ? 'selected' : '' }}>Tipo (A-Z)</option>
								<option value="type_za" {{ $searchData['sort'] == 'type_za' ? 'selected' : '' }}>Tipo (Z-A)</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="searchItemsPerPage">Nº materiais por página:</label>
							<select name="pages" class="form-control">
								<option value="10" {{ $searchData['pages'] == '10' ? 'selected' : '' }}>10</option>
								<option value="20" {{ $searchData['pages'] == '20' ? 'selected' : '' }}>20</option>
								<option value="30" {{ $searchData['pages'] == '30' ? 'selected' : '' }}>30</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group form-inline	">
							<label for="blockedItems">Materiais:</label>
							<select name="blocked" class="form-control">
								<option value="all" {{ $searchData['blocked'] == 'all' ? 'selected' : '' }}>Ver Todos</option>
								<option value="just_blocked" {{ $searchData['blocked'] == 'just_blocked' ? 'selected' : '' }}>Só Bloqueados</option>
								<option value="just_unblocked" {{ $searchData['blocked'] == 'just_unblocked' ? 'just_unblocked' : '' }}>Só Não Bloqueados</option>
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