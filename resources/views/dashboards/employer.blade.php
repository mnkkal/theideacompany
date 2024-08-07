@extends('layouts.app')
@section('title')
    {{ __('Job Applications') }}
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
                        <div
                            class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('Job Applications') }}
                                </div>
                             
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                  
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                <a href="{{ route('jobs.create') }}">
                                    <button type="button" class="my-2 pull-right btn btn-primary new-user-btn filter-container__btn ms-sm-0 ms-auto">
                                        {{ __('New Jobs') }}
                                    </button>
                                </a>
                                </div>
                             
                            </div>
                        </div>
                        <form action="{{ route('dashboard.index') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search for pitches..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <div class="card-body">
                        <table class="table  table-borderless table-responsive-sm table-responsive-lg table-responsive-md table-responsive-xl" id="jobs_table">
    <thead>
    <tr>
    <th>No</th>
            <th>Job Post</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th width="280px">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($applications as $application)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $application->job->job_post }}</td>
                <td>{{ $application->name }}</td>
                <td>{{ $application->email }}</td>
                <td>{{ $application->phone }}</td>
                <td>
                   
                        <a class="btn btn-info" href="{{ route('job-applications.show', $application->id) }}">Show</a>
                     
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

                            <div class="pull-right me-3">

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
        let defaultImageAvatar = "{{ getDefaultAvatar() }}"
    </script>
  
@endsection

