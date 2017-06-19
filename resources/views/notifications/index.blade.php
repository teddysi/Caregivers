@extends ('layouts.master')

@section ('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <legend>Notificações</legend>
            @if (count($notifications))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Notificação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
							<tr>
					        	<td>{{ $notification->created_at }}
                                    @if ($notification->viewed == 0)
                                        <span class="label label-danger">NOVO!</span>
                                    @endif
                                </td>
								<td>{{ $notification->text }}</td>
								<td>
                                    <div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
                                            @if ($notification->type == 'evaluation')
                                                <a class="btn btn-block btn-primary" href="{{ route('evaluations.show', ['evaluation' => $notification->evaluation_id]) }}">Detalhes</a>
                                            @else
                                                <a class="btn btn-block btn-primary" href="{{ route('caregivers.rate', ['caregiver' => $notification->creator->id]) }}">Detalhes</a>
                                            @endif
										</div>
									</div>
                                </td>
					        </tr>
				        @endforeach
                    </tbody>
                </table>
			@else
				<h4>Não existem notificações.</h4>
			@endif
        </div>
	</div>
</div>	

@endsection