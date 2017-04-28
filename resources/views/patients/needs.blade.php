@extends ('layouts.master')

@section('title', 'Lista de Necessidades')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1>Necessidades de {{ $patient->name }}</h1>
			@if (count($needs))
				<br /><br />
				<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Descrição</th>
							<th>Criador</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($needs as $need)
							<tr>
								<td>{{ $need->description }}</td>
								<td>{{ $need->creator->username }}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem necessidades.</h4>
			@endif
			<div class="text-center">
				{!! $needs->links() !!}
			</div>
        </div>
	</div>
</div>

@endsection