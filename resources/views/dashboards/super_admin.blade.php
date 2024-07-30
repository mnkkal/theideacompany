@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Super Admin Dashboard</h1>
    <p>Investor Count: {{ $investorCount }}</p>
    <p>Startup Count: {{ $startupCount }}</p>
    <!-- Add more stats or details as needed -->
</div>
@endsection
