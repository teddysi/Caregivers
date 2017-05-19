@extends ('layouts.master')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Questionários</h1>
			<fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{route('quizs.create')}}">Questionário</a>
					</div>
				</div>
			</fieldset>
			<br /><br />
			
            @if (count($quizs))
                <br />
                <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Criador</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($quizs as $quiz)
							<tr>
					        	<td>{{ $quiz->name }}</td>
								<td>{{ $quiz->creator->username }}</td>
								<td style="width:37%">
									<div class="row">
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-primary" href="">Ver</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<a class="btn btn-block btn-warning" href="{{route('quizs.edit', ['quiz' => $quiz->id])}}">Editar</a>
										</div>
										<div class="col-sm-6 col-md-4 col-lg-4">
											<form action="{{route('quizs.delete', ['quiz' => $quiz->id])}}" method="POST" class="form-group">
												{{ method_field('DELETE') }}
										        {{ csrf_field() }}
												<button type="submit" class="btn btn-block btn-danger" name="save">Eliminar</button>
										    </form>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem questionários.</h4>
			@endif
        </div>
	</div>
</div>	

@endsection