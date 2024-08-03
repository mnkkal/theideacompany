@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Startup Dashboard</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <!-- Buttons -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('investors.create') }}" class="btn btn-success">Create a Pitch</a>
    </div>
    
    <div class="row">
        @foreach($pitches as $pitch)
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $pitch->name_of_venture }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Name of Founder/s:</strong>
                        </div>
                        <div class="col-md-3">
                            @foreach ($pitch->founders as $founder)
                                <p>{{ $founder->name }}</p>
                            @endforeach
                        </div>
                   
                        <div class="col-md-3">
                            <strong>Qualifications:</strong>
                        </div>
                        <div class="col-md-3">
                            @foreach ($pitch->founders as $founder)
                                <p>{{ $founder->qualification }}</p>
                            @endforeach
                        </div>
                  
                        <div class="col-md-3">
                            <strong>Logo:</strong>
                        </div>
                        <div class="col-md-3">
                            <img src="{{ asset('uploads/' . $pitch->company_logo) }}" alt="Company Logo" width="100">
                        </div>
                   
                        <div class="col-md-3">
                            <strong>Sector:</strong>
                        </div>
                        <div class="col-md-3">
                            <p>{{ $pitch->sector }}</p>
                        </div> 
                        <div class="col-md-3">
                            <strong>Problem/Opportunity:</strong>
                        </div>
                        <div class="col-md-9">
                            <p>
                            {{ Str::limit($pitch->problem_opportunity, 100, '...') }}
                           </p>
                        </div>
                 
                        
                    
                  
                        <div class="col-md-3">
                            <strong>Solution/Technology:</strong>
                        </div>
                        <div class="col-md-9">

                            <p>
                            {{ Str::limit($pitch->solution_technology, 100, '...') }}
                           </p>
                        </div>
                   
                   
                        <div class="col-md-3">
                            <strong>Investment Amount:</strong>
                        </div>
                        <div class="col-md-9">
                            <p>Equity: Rs.{{ $pitch->equity_amount }} + Debt: Rs.{{ $pitch->debt_amount }}</p>
                            <p>Total Rs.: {{ $pitch->equity_amount + $pitch->debt_amount }}</p>
                            <p>Equity Split: {{$pitch->equity_offered}} equity</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                   
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <p>{{ $pitch->likes }} Likes</p>
                        </div>
                        <div class="col-md-3">
                            <p>{{ $pitch->dislikes }} Dislikes</p>
                        </div>
                        <div class="col-md-3">
                            <p>{{ $pitch->views }} Views</p>
                        </div>
                        <div class="col-md-3">
                            <p>1/7 days used</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('investors.edit', $pitch->id) }}" class="btn btn-warning">Edit</a>
                        </div>
                        <div class="col-md-9">
                            <!-- Additional empty columns to maintain alignment -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
