@extends ('layouts.master')


@section ('content')

<div class="container">
	    <a class="btn btn-primary" href="{{url('/all_users')}}">Todos os utilizadores</a>

<br /><br />

	    <a class="btn btn-primary" href="{{url('/admins')}}">Administradores</a>

<br /><br />

	    <a class="btn btn-primary" href="{{url('/healthcarepros')}}">Profissionais de saude</a>

<br /><br />


	    <a class="btn btn-primary" href="{{route('admin.admin_caregivers')}}">Cuidadores</a>

<br /><br />

	    <a class="btn btn-primary" href="{{url('/patients')}}">Pacientes</a>

<br /><br />

	    <a class="btn btn-primary" href="{{url('/needs')}}">Necessidades</a>

<br /><br />

	    <a class="btn btn-primary" href="{{url('/materials')}}">Materiais</a>

</div>
	

@endsection

 