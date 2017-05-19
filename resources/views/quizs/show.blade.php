@extends ('layouts.master')

@section('title', 'Material')

@section ('content')

<div class="container">
    <h2><strong>Nome:</strong> {{ $quiz->name }}</h2>
    <h4><strong>Criador:</strong> {{ $quiz->created_by }}</h4>
	<h4><strong>Data:</strong> {{ $quiz->created_at }}</h4>

	<div class="row">
            <div class="col-lg-12">
                <legend>Questionário</legend>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Perguntas</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($quizQuestions as $q)
                        <tr>
	                        <td>{{ $q->question }}</td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
            </div>
        </div>

@endsection