@extends('layouts.admin')

@section('content')
    <h1>Test Dashboard</h1>
    <p>Total de Pacientes: {{ $data['total_patients'] }}</p>
@endsection
