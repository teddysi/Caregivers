@extends ('layouts.master')

@section('title', 'Lista de Materiais')

@section ('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1>Materiais de {{ $need->description }}</h1>
			@if (count($materials))
				<br /><br />
				<legend>Listar</legend>
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Nome</th>
							<th>Descrição</th>
							<th>Tipo</th>
							<th>Criador</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($materials as $material)
							<tr>
					        	<td>{{ $material->name }}</td>
								<td>{{ $material->description }}</td>
                                <td>{{ $material->type }}</td>
								<td>{{ $material->creator->username }}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem materiais.</h4>
			@endif
			<div class="text-center">
				{!! $materials->links() !!}
			</div>
        </div>
	</div>
</div>

@endsection