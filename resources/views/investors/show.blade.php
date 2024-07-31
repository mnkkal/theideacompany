@extends('layouts.app')

@section('title')
    {{ __('Investor Details') }}
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
                        <div class="card-header">
                            <h3>{{ __('Investor Details') }}</h3>
                        </div>
                        <div class="card-body">
                            @can('manage_investors')
                            <a href="{{ route('investors.index') }}" class="btn btn-secondary mb-3">Back to Investors List</a>
                            @endcan
                            <div class="mb-3">
                                <h4>About Founder/s</h4>
                                @foreach ($investor->founders as $founder)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <p><strong>Name:</strong> {{ $founder->name }}</p>
                                            <p><strong>Qualification:</strong> {{ $founder->qualification }}</p>
                                            <p><strong>Experience Summary:</strong> {{ $founder->experience_summary }}</p>
                                            <p><strong>Key Skills:</strong> {{ $founder->key_skills }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mb-3">
                                <h4>Concept of Venture</h4>
                                <p><strong>Problem/Opportunity:</strong> {{ $investor->problem_opportunity }}</p>
                                <p><strong>Solution/Technology:</strong> {{ $investor->solution_technology }}</p>
                                <p><strong>Current Stage:</strong> {{ $investor->current_stage }}</p>
                                @if ($investor->product_demo)
                                    <p><strong>Product Demo:</strong> <a href="{{ asset('storage/' . $investor->product_demo) }}" target="_blank">View Demo</a></p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h4>Business Model and Specification</h4>
                                <p><strong>Unique Value Proposition:</strong> {{ $investor->unique_value_proposition }}</p>
                                <p><strong>Competitive Advantage:</strong> {{ $investor->competitive_advantage }}</p>
                                <p><strong>Target Customer Segment/Market Size:</strong> {{ $investor->target_customer_segment }}</p>
                                <p><strong>Channels and Strategies:</strong> {{ $investor->channels_strategies }}</p>
                                <p><strong>Revenue Streams:</strong> {{ $investor->revenue_streams }}</p>
                                <p><strong>Costs and Expenditures:</strong> {{ $investor->costs_expenditures }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h4>Vision and Future Prospects</h4>
                                <p><strong>24 Month Plan:</strong> {{ $investor->plan_24_month }}</p>
                                <p><strong>Why Are You Applying:</strong> {{ $investor->why_applying }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h4>Pitch Deck and Other Official Documents</h4>
                                @if ($investor->pitch_deck)
                                    <p><strong>Pitch Deck:</strong> <a href="{{ asset('storage/' . $investor->pitch_deck) }}" target="_blank">Download Pitch Deck</a></p>
                                @endif
                                @if ($investor->certificate_incorporation)
                                    <p><strong>Certificate of Incorporation:</strong> <a href="{{ asset('storage/' . $investor->certificate_incorporation) }}" target="_blank">Download Certificate</a></p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h4>Investment Ask</h4>
                                <p><strong>Capital Required:</strong> {{ $investor->capital_required }}</p>
                                <p><strong>Form of Investment Preferred:</strong> {{ ucfirst($investor->investment_preferred) }}</p>
                                @if ($investor->equity_amount)
                                    <p><strong>Equity Amount:</strong> {{ $investor->equity_amount }}</p>
                                @endif
                                @if ($investor->debt_amount)
                                    <p><strong>Debt Amount:</strong> {{ $investor->debt_amount }}</p>
                                @endif
                                @if ($investor->equity_offered)
                                    <p><strong>Equity Offered Against Capital Ask:</strong> {{ $investor->equity_offered }}%</p>
                                @endif
                            </div>
                            @can('pitch_edit')
                            <a href="{{ route('investors.edit', $investor->id) }}" class="btn btn-warning">Edit</a>
                            @endcan
                            @can('pitch_delete')
                            <form action="{{ route('investors.destroy', $investor->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endcan
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
@endsection
