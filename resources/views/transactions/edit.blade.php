@extends('layouts.app')

@section('title')
    {{ __('Edit Transaction') }}
@endsection

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection

@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('Edit Transaction') }}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="user_id">User:</label>
                                        <select name="user_id" class="form-control">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subscription_plan_id">Subscription Plan:</label>
                                        <select name="subscription_plan_id" id="subscription_plan_id" class="form-control">
                                            <option value="">None</option>
                                            @foreach($subscriptionPlans as $plan)
                                                <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" {{ $transaction->subscription_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->price }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount:</label>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" value="{{ $transaction->amount }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" class="form-control">
                                            <option value="successful" {{ $transaction->status == 'successful' ? 'selected' : '' }}>Successful</option>
                                            <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="processed">Processed:</label>
                                        <select name="processed" class="form-control">
                                            <option value="0" {{ !$transaction->processed ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $transaction->processed ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var subscriptionPlanSelect = document.getElementById('subscription_plan_id');
            var amountInput = document.getElementById('amount');

            subscriptionPlanSelect.addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var price = selectedOption.getAttribute('data-price');
                amountInput.value = price ? price : '';
            });
        });
    </script>
@endsection
