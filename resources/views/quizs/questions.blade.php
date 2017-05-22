@extends ('layouts.master')

@section('title', 'Gerênciar Perguntas do Questionário')

@section ('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-5 col-lg-5">
            <legend>Outras Perguntas</legend>
            @if (count($notQuizQuestions))
		        <table class="table table-striped">
			        <thead>
			            <tr>
			                <th>Pergunta</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($notQuizQuestions as $notQuizQuestion)
							<tr>
					        	<td>{{ $notQuizQuestion->question }}</td>
								<td>
									<form action="{{ route('quizs.addQuestion', ['quiz' => $quiz->id, 'question' => $notQuizQuestion->id]) }}" method="POST" class="form-group">
									    {{ csrf_field() }}
										<div class="form-group">
											<button type="submit" class="btn btn-block btn-success" name="add">Adicionar</button>
										</div>
								    </form>
								</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
				<h4>Não existem perguntas para adicionar.</h4>
			@endif
			<div class="text-center">
				{!! $notQuizQuestions->links() !!}
			</div>
		</div>
        <div class="col-sm-12 col-md-7 col-lg-7">
            <legend>Perguntas Associadas</legend>
            @if (count($quizQuestions))
		        <table class="table table-striped">
			        <thead>
			            <tr>
                            <th>Ordem</th>
			                <th>Pergunta</th>
							<th>Ações</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach ($quizQuestions as $index => $quizQuestion)
							<tr>
                                <td>{{ $index + 1 }}</td>
					        	<td>{{ $quizQuestion->question }}</td>
								<td>
                                    <div class="row">
                                        @if ($index != 0)
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <form action="{{ route('quizs.upQuestion', ['quiz' => $quiz->id, 'question' => $quizQuestion->id]) }}" method="POST" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-block btn-primary" name="up">Cima</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-sm-6 col-md-4 col-lg-4"></div>
                                        @endif
                                        @if (count($quizQuestions) != ($index + 1))
                                            <div class="col-sm-6 col-md-4 col-lg-4">
                                                <form action="{{ route('quizs.downQuestion', ['quiz' => $quiz->id, 'question' => $quizQuestion->id]) }}" method="POST" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-block btn-primary" name="down">Baixo</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-sm-6 col-md-4 col-lg-4"></div>
                                        @endif
                                        <div class="col-sm-6 col-md-4 col-lg-4">
                                            <form action="{{ route('quizs.removeQuestion', ['quiz' => $quiz->id, 'question' => $quizQuestion->id]) }}" method="POST" class="form-group">
                                            {{ csrf_field() }}
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-block btn-danger" name="remove">Remover</button>
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
				{!! $quizQuestions->links() !!}
			</div>
		</div>
    </div>
    <p><a class="btn btn-primary" href="{{ url('/') }}">Concluído</a></p>
</div>

@endsection