@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Investor Dashboard</h1>
    @foreach($pitches as $pitch)
    <div class="pitch">
        <h2>{{ $pitch->title }}</h2>
        <p>{{ $pitch->description }}</p>
        <p>Views: {{ $pitch->views }}</p>
        <p>Likes: {{ $pitch->likes }}</p>
        <p>Dislikes: {{ $pitch->dislikes }}</p>
        <form action="{{ route('investor.pitch.like', $pitch) }}" method="POST">
            @csrf
            <button type="submit">Like</button>
        </form>
        <form action="{{ route('investor.pitch.dislike', $pitch) }}" method="POST">
            @csrf
            <button type="submit">Dislike</button>
        </form>
        <form action="{{ route('investor.pitch.interest', $pitch) }}" method="POST">
            @csrf
            <button type="submit">Show Interest</button>
        </form>
    </div>
    @endforeach
</div>
@endsection
