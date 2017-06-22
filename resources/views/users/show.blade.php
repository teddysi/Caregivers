@extends ('layouts.master')

@section('title', 'Utilizador')

@section ('content')
<div class="container">
    <h2><strong>Utilizador:</strong> {{ $user->username }}</h2>
    <div class="row">
		<div class="col-sm-12 col-md-8 col-lg-8"> 
            <h4><strong>Nome:</strong> {{ $user->name }}</h4>
            <h4><strong>Email:</strong> {{ $user->email }}</h4>
            <h4><strong>Função:</strong> {{ $user->role }}</h4>
            @if ($user->role == 'Profissional de Saúde')
                <h4><strong>Trabalho/Estatuto:</strong> {{ $user->job }}</h4>
                <h4><strong>Local de Trabalho:</strong> {{ $user->facility }}</h4>
            @endif
            @if ($user->role == 'Cuidador')
                <h4><strong>Localização:</strong> {{ $user->location }}</h4>
                <h4><strong>Nº Profissionais de Saúde:</strong> {{ count($user->healthcarePros) }}/2</h4>
                <h4><strong>Criador:</strong> {{ $user->creator->username }}</h4>
            @endif
            <h4><strong>Data da criação:</strong> {{ $user->created_at }}</h4>
            <h4><strong>Data da última atualização:</strong> {{ $user->updated_at }}</h4>
 		</div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ações</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-block btn-warning" href="{{ route('users.edit', ['user' => $user->id]) }}">Editar</a>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <form action="{{ route('users.toggleBlock', ['user' => $user->id]) }}" method="POST" class="form-group">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    @if ($user->blocked == 0)
                                        <button type="submit" class="btn btn-block btn-danger" name="block">Bloquear</button>
                                    @elseif ($user->blocked == 1)
                                        <button type="submit" class="btn btn-block btn-success" name="unblock">Desbloquear</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (Auth::user()->role == 'healthcarepro' && $user->role == 'Cuidador' && $isMyCaregiver)
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-primary" href="{{ route('caregivers.patients', ['caregiver' => $user->id]) }}">Pacientes</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-primary" href="{{ route('caregivers.materials', ['caregiver' => $user->id]) }}">Materiais</a>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-primary" href="{{ route('caregivers.rate', ['caregiver' => $user->id]) }}">Avaliações</a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-block btn-default" href="javascript:history.back()">Voltar Atrás</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
 		</div>
	</div>
    <br />
    <div class="row">
		<div class="col-lg-12">
            <legend>Registos</legend>
			@if (count($logs))
		        <table class="table table-striped">
			        <thead>
			            <tr>
							<th>Tarefa</th>
							<th>Realizada por</th>
                            <th>Data</th>
			            </tr>
			        </thead>
			        <tbody>
						@foreach($logs as $log)
							<tr>
					        	<td>{{$log->performed_task}}</td>
								<td>{{$log->doneBy->username}}</td>
                                <td>{{$log->created_at}}</td>
					        </tr>
				        @endforeach
					</tbody>
			    </table>
			@else
                @if (Auth::user()->role == 'admin')
				    <h4>Não existem registos referentes a este Utilizador.</h4>
                @elseif (Auth::user()->role == 'healthcarepro')
                    <h4>Não existem registos referentes a este Cuidador.</h4>
                @endif
			@endif
 		</div>
	</div>
</div>

@endsection