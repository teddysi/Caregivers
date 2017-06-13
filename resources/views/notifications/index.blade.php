@extends ('layouts.master')

@section ('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
            <h1>Notificações</h1>
            @if (count($allNotifications))
                <br />
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Notificação</th>
                            <th>Cuidador</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allNotifications as $notification)
							<tr>
					        	<td>{{ $notification->created_at }}
                                    @if ($notification->viewed == 0)
                                        <span class="label label-danger">NOVO!</span>
                                    @endif
                                </td>
								<td>{{ $notification->text }}</td>
								<td>{{ $notification->creator->username }}</td>
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