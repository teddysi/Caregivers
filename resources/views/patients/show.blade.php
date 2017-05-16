@extends ('layouts.master')

@section('title', 'Paciente')

@section ('content')
<div class="container">
    <h2><strong>Paciente:</strong> {{ $patient->name }}</h2>
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
    <div class="row">
		<div class="col-lg-12">
            <h2>Avaliações</h2>
            <fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('patients.evaluations.create', ['id' => $patient->id]) }}">Avaliação</a>
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
    <p><a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a></p>
</div>

@endsection