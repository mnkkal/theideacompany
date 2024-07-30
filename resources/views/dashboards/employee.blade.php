@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Dashboard</h1>
    @foreach($tasks as $task)
    <div class="task">
        <h2>{{ $task->title }}</h2>
        <p>{{ $task->description }}</p>
        <p>Due Date: {{ $task->due_date }}</p>
        <p>Status: {{ $task->status }}</p>
    </div>
    @endforeach
</div>
@endsection
