@extends ('layouts.master')

@section('title', 'Paciente')

@section ('content')
<div class="container">
    <h2><strong>Paciente:</strong> {{ $patient->name }}</h2>
    <h4><strong>Email:</strong> {{ $patient->email }}</h4>
    <h4><strong>Localização:</strong> {{ $patient->location }}</h4>
    <h4><strong>Cuidador:</strong> 
        @if ($patient->caregiver)
            {{ $patient->caregiver->username }}
        @else
            Não tem Cuidador
        @endif
    </h4>
    <h4><strong>Criador:</strong> {{ $patient->creator->username }}</h4>
    <h4><strong>Data da criação:</strong> {{ $patient->created_at }}</h4>
    <h4><strong>Data da última atualização:</strong> {{ $patient->updated_at }}</h4>
    <p><a class="btn btn-default" href="javascript:history.back()">Voltar a atrás</a></p>
</div>

@endsection