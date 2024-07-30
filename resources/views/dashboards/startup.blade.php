@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Startup Dashboard</h1>
    @foreach($pitches as $pitch)
    <div class="pitch">
        <h2>{{ $pitch->title }}</h2>
        <p>{{ $pitch->description }}</p>
        <p>Views: {{ $pitch->views }}</p>
        <p>Likes: {{ $pitch->likes }}</p>
        <p>Dislikes: {{ $pitch->dislikes }}</p>
        <p>Days left: {{ $pitch->days_left }}</p>
        <a href="{{ route('startup.pitches.edit', $pitch->id) }}" class="btn btn-primary">Edit Pitch</a>
    </div>
    @endforeach
    <a href="{{ route('startup.chat') }}" class="btn btn-primary">Chat with Admin</a>
</div>
@endsection
