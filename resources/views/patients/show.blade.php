@extends ('layouts.master')

@section('title', 'Utente')

@section ('content')
<div class="container">
    <h2><strong>Utente:</strong> {{ $patient->name }}</h2>
	<div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8">
			<h4><strong>Email:</strong> {{ $patient->email }}</h4>
			<h4><strong>Localização:</strong> {{ $patient->location }}</h4>
			<h4><strong>Cuidador:</strong> 
				@if ($patient->caregiver)
					{{ $patient->caregiver->username }}
				@else
					Não tem Cuidador
				@endif
			</h4>
			<h4><strong>Criador:</strong> {{ $patient->creator->username }}</h4>
			<h4><strong>Data da criação:</strong> {{ $patient->created_at }}</h4>
			<h4><strong>Data da última atualização:</strong> {{ $patient->updated_at }}</h4>
		</div>
		<div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('patients.edit', ['patient' => $patient->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-primary" href="{{ route('patients.needs', ['patient' => $patient->id]) }}">Necessidades</a>
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
            <legend>Avaliações</legend>
			<div class="row">
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('patients.evaluations.create', ['id' => $patient->id, 'typeEval' => 'eval']) }}">Nova Avaliação</a>
				</div>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('patients.evaluations.create', ['id' => $patient->id, 'typeEval' => 'quiz']) }}">Disponiblizar Questionário</a>
				</div>
			</div>
			<br />
			@if (count($evaluations))
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Descrição</th>
							<th>Tipo</th>
							<th>Modelo</th>
							<th>Realizada por</th>
							<th>Data de Criação</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach($evaluations as $evaluation)
							<tr>
					        	<td>{{$evaluation->description}}</td>
								<td>{{$evaluation->type}}</td>
								<td>{{$evaluation->model}}</td>
                                <td>{{$evaluation->creator->username}}</td>
                                <td>{{$evaluation->created_at}}</td>
                                <td>
									<div class="row">
										<div class="col-sm-6 col-md-6 col-lg-6">
											<a class="btn btn-block btn-primary" href="{{ route('evaluations.show', ['evaluation' => $evaluation->id]) }}">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-6 col-lg-6">
											<a class="btn btn-block btn-warning" href="{{ route('evaluations.edit', ['evaluation' => $evaluation->id]) }}">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem avaliações realizadas a este Utente.</h4>
			@endif
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
				<h4>Não existem registos referentes a este Utente.</h4>
			@endif
 		</div>
	</div>
</div>

@endsection