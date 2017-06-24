@extends ('layouts.master')

@section('title', 'Avaliações Cuidador')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Avaliações de {{ $caregiver->name }}</legend>
            <div class="row">
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('caregivers.evaluations.create', ['id' => $caregiver->id, 'type' => 'eval']) }}">Nova Avaliação</a>
				</div>
				<div class="col-4 col-sm-4 col-md-4">
					<a class="btn btn-block btn-primary" href="{{ route('caregivers.evaluations.create', ['id' => $caregiver->id, 'type' => 'quiz']) }}">Disponiblizar Questionário</a>
				</div>
			</div>
			<br />
			@if (count($evaluations))
		        <table class="table table-striped evaluations">
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
				<h4>Não existem avaliações realizadas a este Cuidador.</h4>
			@endif
 		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-6">
			<legend>Acessos a Materiais</legend>
            @if (count($countedAccesses))
		        <table class="table table-striped materials-access">
			        <thead>
			            <tr>
							<th>Material utilizado</th>
							<th>Contagem</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($countedAccesses as $countedAccess)
							<tr>
					        	<td>{{ $countedAccess->name }}</td>
								<td>{{ $countedAccess->total }}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem acessos a materiais realizados por este Cuidador.</h4>
			@endif
			<div class="text-center">
				{!! $countedAccesses->links() !!}
			</div>
        </div>
	</div>
	<br />
	<p><a class="btn btn-default" href="javascript:history.back()">Voltar Atrás</a></p>
</div>

@endsection