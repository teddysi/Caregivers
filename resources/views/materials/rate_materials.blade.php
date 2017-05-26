@extends ('layouts.master')

@section('title', 'Avaliações Material')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Avaliações do Material {{ $material->name }}</h1>
            <fieldset>
				<legend>Criar</legend>
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('materials.create_for_materials', ['id' => $caregiver->id, 'material' => $material->id]) }}">Disponiblizar Questionário</a>
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
</div>

@endsection