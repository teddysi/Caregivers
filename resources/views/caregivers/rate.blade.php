@extends ('layouts.master')

@section('title', 'Avaliar Cuidador')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Avaliar {{ $caregiver->name }}</h1>
            <fieldset>
				<legend>Criar</legend>
				<div class="row">
					<div class="col-4 col-sm-4 col-md-4">
						<a class="btn btn-block btn-primary" href="{{ route('caregivers.evaluations.create', ['id' => $caregiver->id])}}">Avaliação</a>
					</div>
				</div>
			</fieldset>
			<br /><br />
			<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Descrição</th>
							<th>Realizada por</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach($evaluations as $eva)
							<tr>
					        	<td>{{$eva->name}}</td>
								<td>{{$eva->description}}</td>
                                <td>{{$eva->creator->username}}</td>
								<td style="width:50%">
									<div class="row">
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-primary" href="{">Detalhes</a>
										</div>
										<div class="col-sm-6 col-md-3 col-lg-3">
											<a class="btn btn-block btn-warning" href="">Editar</a>
										</div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>

            @if (count($countedProceedings))
				<br />
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
    <div class="row">
		<div class="col-lg-6">
            <legend>Avaliar</legend>
		    <form action="{{ url('/caregivers', ['caregiver' => $caregiver->id]) }}" method="POST" class="form-group">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                <div class="form-group form-inline">
					<label for="rate">Avaliação:</label>
					<select name="rate" class="form-control">
						@foreach ($rates as $rate)
							<option value="{{ $rate }}" {{ $caregiver->rate == $rate ? 'selected' : '' }}>{{ $rate }}</option>
						@endforeach
					</select>
				</div>
            
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="save">Avaliar</button>
                    <a class="btn btn-default" href="javascript:history.back()">Cancelar</a>
                </div>
                @include('layouts.errors')
            </form>
        </div>
	</div>
</div>

@endsection