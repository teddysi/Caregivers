@extends ('layouts.master')

@section('title', 'Material')

@section ('content')
<div class="container">
    <h2><strong>Material:</strong> {{ $material->name }}</h2>
    <h4><strong>Tipo:</strong> {{ $material->type }}</h4>
    <h4><strong>Descrição:</strong> {{ $material->description }}</h4>
    @if ($material->type == 'Texto')
        <h4><strong>Texto:</strong> {{ $material->body }}</h4>
    @endif
    @if ($material->type == 'Imagem' || $material->type == 'Video' || ($material->type == 'Anexo' && $material->path))
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
    @if ($material->type == 'Composto')
        <div class="row">
            <div class="col-lg-12">
                @if (count($compositeMaterials))
                    <legend>Materiais Associados</legend>
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
                    <h4>Não existem materiais associados.</h4>
                @endif
                <div class="text-center">
                    {!! $compositeMaterials->links() !!}
                </div>
            </div>
        </div>
    @endif
    
    <p>
        @if ($material->type == 'Composto')
            <a class="btn btn-primary" href="{{ route('materials.materials', ['material' => $material->id]) }}">Modificar materiais associados</a>
        @endif
        <a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a>
    </p>
</div>

@endsection