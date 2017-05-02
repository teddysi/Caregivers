@extends ('layouts.master')

@section('title', 'Avaliar Cuidador')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-6">
            <h1>Avaliar {{ $caregiver->name }}</h1>
            @if (count($countedProceedings))
				<br />
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