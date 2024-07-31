@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <p>{{ $userCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Transactions This Month</div>
                <div class="card-body">
                    <p>{{ $transactionCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Income This Month</div>
                <div class="card-body">
                    <p>Rs {{ number_format($income, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">{{ $role->name }} Users</div>
                <div class="card-body">
                    <p>{{ $role->users_count }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
