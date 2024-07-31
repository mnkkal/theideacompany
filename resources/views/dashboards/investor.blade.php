@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
<div class="container">
    <h1>Investor Dashboard</h1>
    <div class="d-flex justify-content-end mb-3">
       
        <a href="{{ route('investors.create') }}" class="btn btn-success">Create a Pitch</a>
    </div>
    <table class="table table-striped mb-5">
       
        <tbody>
            @foreach($pitches as $pitch)
            
            <tr>
                <th>Name of venture</th>
                <th>Name of founder/s </th>
                <th>Qualifications</th>
                <th>Logo</th>
            </tr>
             <tr>
                <td>{{ $pitch->name_of_venture }}</td>
                <td> 
                    @foreach ($pitch->founders as $index => $founder)
                    {{ $founder->name }}
                    @endforeach
                </td>
                <td>
                @foreach ($pitch->founders as $index => $founder)
                    {{ $founder->qualification }}
                @endforeach
                </td>
                <td> <img src="{{ asset('uploads/' . $pitch->company_logo) }}" alt="Company Logo" width="100"> </td>
            </tr>
            <tr>
                <th>problem/opportunity </th>
                <th></th>
                <th></th>
                <th>Sector</th>
              
              
            </tr>
            <tr>
             <td>{{ $pitch->problem_opportunity }}</td>
             <td></td>
             <td></td>
             <td>{{ $pitch->sector }}</td>
           
            </tr>
            <tr>
                <th>solution/Technology </th>
                <th></th>
                <th></th>
                <th></th>
              
            </tr>
            <tr>
            <td>{{ $pitch->solution_technology }}</td>
                <td></td>
                <td></td>
                <td></td>
               
           
            </tr>
            <tr>
                <th>Investment amount  </th>
                <th></th>
                <th></th>
                <th></th> 
            </tr>
            <tr>
                <td>
                   <label for="">Equity: Rs.{{ $pitch->equity_amount }} + Debt: Rs.{{ $pitch->debt_amount }}</label> 
                <p>Total Rs. : {{ $pitch->equity_amount + $pitch->debt_amount }} </p>
                </td>
                <td></td>
                <td>equity split:  
                    {{$pitch->equity_offered }}  equity

                </td>
                <td></td>
           
            </tr>
           
          
            <tr>
                <th> <form action="{{ route('investor.pitch.like', $pitch) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Like</button>
                    </form></th>
                <th> <form action="{{ route('investor.pitch.dislike', $pitch) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Dislike</button>
                    </form></th>
                <td><form action="{{ route('investor.pitch.interest', $pitch) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Show Interest</button>
                    </form></td>
                    <td> <a href="#" class="btn btn-info mr-2">Chat with our team to invest</a></td>
              
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            @endforeach
        </tbody>
    </table>

</div>
@endsection
