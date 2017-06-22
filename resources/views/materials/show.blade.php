@extends ('layouts.master')

@section('title', 'Material')

@section ('content')
<div class="container">
    <h2><strong>Material:</strong> {{ $material->name }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Tipo:</strong> {{ $material->type }}</h4>
            <h4><strong>Descrição:</strong> {{ $material->description }}</h4>
            @if ($material->type == 'Texto')
                <h4><strong>Texto:</strong> {{ $material->body }}</h4>
            @endif
            @if ($material->type == 'Imagem')
                <h4><strong>Ficheiro:</strong></h4> 
                <img src="{{ route('materials.showContent', ['material' => $material->id] )}}" alt="{{ $material->name}}">
            @endif
            @if ($material->type == 'Video')
                <h4><strong>Ficheiro:</strong></h4> 
                <video controls autoplay name="video" loop>
                    <source src="{{ route('materials.showContent', ['material' => $material->id] )}}" type="video/mp4">
                </video>
            @endif
            @if ($material->type == 'Anexo' && $material->path)
                <h4><strong>Ficheiro:</strong> <a href="{{ route('materials.showContent', ['material' => $material->id] )}}" target="_blank">{{ $material->name.$material->mime }}</a></h4>
            @endif
            @if ($material->type == 'Anexo' && !$material->path)
                <h4><strong>URL:</strong> <a href="{{ $material->url }}" target="_blank">{{ $material->url }}</a></h4>
            @endif
            @if ($material->type == 'Contacto de Emergência')
                <h4><strong>Número:</strong> {{ $material->number }}</h4>
            @endif
            <h4><strong>Criador:</strong> {{ $material->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $material->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $material->updated_at }}</h4>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['material' => $material->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
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
                    @if ($material->type == 'Composto')
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-primary" href="{{ route('materials.materials', ['material' => $material->id]) }}">Materiais</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
 		</div>
	</div>
    <br />
    @if ($material->type == 'Composto')
        <div class="row">
            <div class="col-lg-12">
                <legend>Materiais Associados</legend>
                @if (count($compositeMaterials))
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ordem</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Criador</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($compositeMaterials as $index => $compositeMaterial)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $compositeMaterial->name }}</td>
                                    <td>{{ $compositeMaterial->type }}</td>
                                    <td>{{ $compositeMaterial->creator->username }}</td>
                                    <td style="width:35%">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <a class="btn btn-block btn-primary" href="{{ route('materials.show', ['material' => $compositeMaterial->id]) }}">Detalhes</a>
                                            </div>
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <a class="btn btn-block btn-warning" href="{{ route('materials.edit', ['material' => $compositeMaterial->id]) }}">Editar</a>
                                            </div>
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <form action="{{ route('materials.toggleBlock', ['material' => $compositeMaterial->id]) }}" method="POST" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        @if ($compositeMaterial->blocked == 0)
                                                            <button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
                                                        @elseif ($compositeMaterial->blocked == 1)
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
                    <h4>Não existem materiais associados a este Material Composto.</h4>
                @endif
                <div class="text-center">
                    {!! $compositeMaterials->links() !!}
                </div>
            </div>
        </div>
    @endif
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Registos</legend>
			@if (count($logs))
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Tarefa</th>
							<th>Realizada por</th>
                            <th>Data</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach($logs as $log)
							<tr>
					        	<td>{{$log->performed_task}}</td>
								<td>{{$log->doneBy->username}}</td>
                                <td>{{$log->created_at}}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem registos referentes a este Material.</h4>
			@endif
 		</div>
	</div>
</div>

@endsection