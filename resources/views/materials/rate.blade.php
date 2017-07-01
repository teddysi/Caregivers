@extends ('layouts.master')

@section('title', 'Avaliações Material')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Avaliações do Material {{ $material->name }}</legend>
			<div class="row">
				<div class="col-4 col-sm-4 col-md-4">
					@if ($countProvidableQuizs > 0)
						<a class="btn btn-block btn-primary" href="{{ route('materials.evaluations.create', ['id' => $caregiver->id, 'material' => $material->id]) }}">Disponibilizar Questionário</a>
					@else
						<h5>Não existem questionários disponíveis para disponibilizar.</h5>
					@endif
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
				<h4>Não existem avaliações realizadas sobre este Material.</h4>
			@endif
 		</div>
	</div>
	<br />
	<p><a class="btn btn-default" href="javascript:history.back()">Voltar Atrás</a></p>
</div>

@endsection