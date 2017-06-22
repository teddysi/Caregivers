@extends ('layouts.master')

@section('title', 'Necessidade')

@section ('content')
<div class="container">
    <h2><strong>Necessidade:</strong> {{ $need->description }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
            <h4><strong>Criador:</strong> {{ $need->creator->username }}</h4>
            <h4><strong>Data da criação:</strong> {{ $need->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $need->updated_at }}</h4>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('needs.edit', ['need' => $need->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-primary" href="{{ route('needs.materials', ['need' => $need->id]) }}">Materiais</a>
                        </div>
                    </div>
					<br />
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
						</div>
					</div>
                </div>
            </div>
 		</div>
	</div>
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
				<h4>Não existem registos referentes a esta Necessidade.</h4>
			@endif
 		</div>
	</div>
</div>

@endsection