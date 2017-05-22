@extends ('layouts.master')

@section('title', 'Avaliações Cuidador')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Avaliações de {{ $caregiver->name }}</h1>
            <fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('caregivers.evaluations.create', ['id' => $caregiver->id]) }}">Avaliação</a>
					</div>
				</div>
			</fieldset>
			<br /><br />
			<legend>Listar</legend>
			@if (count($evaluations))
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Descrição</th>
							<th>Tipo</th>
							<th>Modelo</th>
							<th>Realizada por</th>
							<th>Data</th>
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
			<legend>Procedimentos</legend>
            @if (count($countedProceedings))
				<br /><br />
				<legend>Procedimentos realizados</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Material utilizado</th>
							<th>Contagem</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($countedProceedings as $countedProceeding)
							<tr>
					        	<td>{{ $countedProceeding->name }}</td>
								<td>{{ $countedProceeding->total }}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem procedimentos realizados por este Cuidador.</h4>
			@endif
        </div>
	</div>
</div>

@endsection