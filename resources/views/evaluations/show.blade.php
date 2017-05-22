@extends ('layouts.master')

@section('title', 'Avaliação')

@section ('content')
<div class="container">
    <h2><strong>Descrição:</strong> {{ $evaluation->description }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Tipo de Avaliação:</strong> {{ $evaluation->type }}</h4>
            <h4><strong>Modelo:</strong> {{ $evaluation->model }}</h4>
            <h4><strong>Ficheiro:</strong> <a href="{{ route('evaluations.showContent', ['evaluation' => $evaluation->id] )}}" target="_blank">{{ $evaluation->description.$evaluation->mime }}</a></h4>
            <h4><strong>Criador:</strong> {{ $evaluation->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $evaluation->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $evaluation->updated_at }}</h4>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('evaluations.edit', ['evaluation' => $evaluation->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar a atrás</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Registros</legend>
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
				<h4>Não existem registros referentes a esta Avaliação.</h4>
			@endif
 		</div>
	</div>
</div>

@endsection