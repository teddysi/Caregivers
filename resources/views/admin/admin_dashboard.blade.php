@extends ('layouts.master')


@section ('content')

<div class="container">
	    <a class="btn btn-primary" href="{{url('/all_users')}}">Todos os utilizadores</a>
	    <div class="pull-right"> </div>

<br>

	    <a class="btn btn-primary" href="{{url('/admins')}}">Administradores</a>
	    <div class="pull-right"> </div>

<br>

	    <a class="btn btn-primary" href="{{url('/healthcarepros')}}">Profissionais de saude</a>
	    <div class="pull-right"> </div>

<br>


	    <a class="btn btn-primary" href="{{route('admin.admin_caregivers')}}">Cuidadores</a>
	    <div class="pull-right"> 

<br>


	    <a class="btn btn-primary" href="{{url('/patients')}}">Pacientes</a>
	    <div class="pull-right"> </div>

<br>


	    <a class="btn btn-primary" href="{{url('/needs')}}">Necessidades</a>
	    <div class="pull-right"> </div>

<br>


	    <a class="btn btn-primary" href="{{url('/materials')}}">Materiais</a>
	    <div class="pull-right"> </div>
</div>
	

@endsection

 