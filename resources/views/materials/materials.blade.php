@extends ('layouts.master')

@section('title', 'Gerir Material Composto')

@section ('content')
<div class="container">
    <div class="row">
        <div class="materiais-para-adicionar col-sm-12 col-md-5 col-lg-5">
            <legend>Outros Materiais</legend>
            @if (count($notCompositeMaterials))
		        <table class="table table-striped">
			        <thead>
			            <tr>
			                <th>Nome</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($notCompositeMaterials as $notCompositeMaterial)
							<tr>
					        	<td>{{ $notCompositeMaterial->name }}</td>
								<td>
									<form action="{{ route('materials.addMaterial', ['composite' => $material->id, 'material' => $notCompositeMaterial->id]) }}" method="POST" class="form-group">
									    {{ csrf_field() }}
										<div class="form-group">
											<button type="submit" class="btn btn-block btn-success {{ 'add_composite_'.$notCompositeMaterial->id.'_button' }}" name="add">Adicionar</button>
										</div>
								    </form>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem materiais para adicionar.</h4>
			@endif
			<div class="text-center">
				{!! $notCompositeMaterials->links() !!}
			</div>
		</div>
        <div class="materiais-adicionados col-sm-12 col-md-7 col-lg-7">
            <legend>Materiais Associados</legend>
            @if (count($compositeMaterials))
		        <table class="table table-striped">
			        <thead>
			            <tr>
                            <th>Ordem</th>
			                <th>Nome</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($compositeMaterials as $index => $compositeMaterial)
							<tr>
                                <td class="{{ 'composite_'.$compositeMaterial->id.'_order' }}">{{ $index + 1 }}</td>
					        	<td class="{{ 'composite_'.$compositeMaterial->id.'_name' }}">{{ $compositeMaterial->name }}</td>
								<td>
                                    <div class="row">
                                        @if ($index != 0)
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <form action="{{ route('materials.upMaterial', ['composite' => $material->id, 'material' => $compositeMaterial->id]) }}" method="POST" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-block btn-primary {{ 'up_composite_'.$compositeMaterial->id.'_button' }}" name="up">Cima</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-sm-6 col-md-4 col-lg-4"></div>
                                        @endif
                                        @if (count($compositeMaterials) != ($index + 1))
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <form action="{{ route('materials.downMaterial', ['composite' => $material->id, 'material' => $compositeMaterial->id]) }}" method="POST" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-block btn-primary {{ 'down_composite_'.$compositeMaterial->id.'_button' }}" name="down">Baixo</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-sm-6 col-md-4 col-lg-4"></div>
                                        @endif
                                        <div class="col-sm-6 col-md-4 col-lg-4">
                                            <form action="{{ route('materials.removeMaterial', ['composite' => $material->id, 'material' => $compositeMaterial->id]) }}" method="POST" class="form-group">
                                            {{ csrf_field() }}
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-block btn-danger {{ 'remove_composite_'.$compositeMaterial->id.'_button' }}" name="remove">Remover</button>
                                                </div>
                                            </form>
                                        </div>
									</div>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem materiais associados.</h4>
			@endif
			<div class="text-center">
				{!! $compositeMaterials->links() !!}
			</div>
		</div>
    </div>
    <p><a class="btn btn-primary composite_conclude_button" href="{{ route('materials') }}">Concluído</a></p>
</div>

@endsection