@extends ('layouts.master')

@section('title', 'Avaliação')

@section ('content')
<div class="container">
    <h2><strong>Avaliação:</strong> {{ $evaluation->description }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Tipo de Avaliação:</strong> {{ $evaluation->type }}</h4>
            <h4><strong>Modelo:</strong> {{ $evaluation->model }}</h4>
            @if ($evaluation->path)
                <h4><strong>Ficheiro:</strong> <a href="{{ route('evaluations.showContent', ['evaluation' => $evaluation->id] )}}" target="_blank">{{ $evaluation->description.$evaluation->mime }}</a></h4>
            @endif
            @if ($evaluation->submitted_by && $evaluation->difficulty)
                <h4><strong>Avaliador:</strong> {{ $evaluation->submitter->username }} </h4>
                <h4><strong>Dificuldade:</strong> {{ $evaluation->difficulty }}
                    @if ($evaluation->difficulty == 'Difícil')
                        <span style="display:inline-block;width:30px;height:1em;margin-left:2em" 
                            class="label label-danger">
                        </span>
                    @elseif ($evaluation->difficulty == 'Fácil')
                        <span style="display:inline-block;width:30px;height:1em;margin-left:2em" 
                            class="label label-success">
                        </span>
                    @else
                        <span style="display:inline-block;width:30px;height:1em;margin-left:2em" 
                            class="label label-warning">
                        </span>
                    @endif
                </h4>
            @endif
            <h4><strong>Criador:</strong> {{ $evaluation->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $evaluation->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $evaluation->updated_at }}</h4>
            @if ($evaluation->answered_at && $evaluation->answered_by)
                <h4><strong>Questionado:</strong> {{ $evaluation->inquired->username }}</h4>
                <h4><strong>Data da resposta:</strong> {{ $evaluation->answered_at }}</h4>
            @elseif (!$evaluation->answered_at && !$evaluation->path && $evaluation->answered_by)
                <h4><strong>Data da resposta:</strong> À espera de resposta</h4>
            @endif
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
                            <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (count($evaluation->answers))
        <br />
        <div class="row">
            <div class="col-lg-12">
                <legend>Respostas</legend>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Questão</th>
                            <th>Resposta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluation->answers as $answer)
                            <tr>
                                <td>{{ $answer->question }}</td>
                                <td>{{ $answer->answer }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>			
            </div>
        </div>
    @endif
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