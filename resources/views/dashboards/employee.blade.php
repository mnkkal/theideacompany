@extends('layouts.app')

@section('title')
    {{ __('Jobs') }}
@endsection

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}" />
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection

@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            <!-- Flash messages for success or error -->
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                @if (Request::is('dashboard'))
                                    <h1>Employee Dashboard</h1>
                                @else
                                <h1>Jobs</h1>
                                @endcan
                                </div>
                            </div>
                            <!-- Filter container -->
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Filter content here -->
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Additional filter content here -->
                                </div>
                            </div>
                        </div>

                        <!-- Search form -->
                        @if (Request::is('dashboard'))
                        <form action="{{ route('dashboard.index') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search for Jobs..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <form action="{{ route('joblist.index') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search for Jobs..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        @endcan

                        <!-- Job Listings in Card Layout -->
                        <div class="card-body">
                            <div class="row">
                                @foreach ($jobs as $job)
                                    <div class="col-12 mb-4"> <!-- Full-width card -->
                                        <div class="card h-100">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="card-title mb-0">{{ $job->job_post }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Company Name:</strong> {{ $job->company_name }}</p>
                                                <div class="row">
    <div class="col-12 col-md-3">
        <p><strong>Job Type:</strong></p>
        @foreach (json_decode($job->job_type) as $type)
            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
        @endforeach
    </div>
    <div class="col-12 col-md-3">
        <p class="mt-2"><strong>Apply By:</strong> {{ \Carbon\Carbon::parse($job->apply_by)->diffForHumans() }}</p>
    </div>
    <div class="col-12 col-md-3">
        <p><strong>Salary:</strong> {{ $job->salary }}</p>
    </div>
    <div class="col-12 col-md-3">
        <!-- This column is empty, but you can add content here if needed -->
    </div>
</div>

                                                
                                               
                                                <p><strong>About Job:</strong> 
                                                {{ Str::limit($job->about_job, 100, '...') }}
                                                <p><strong>About Company:</strong> 
                                                {{ Str::limit($job->about_company, 100, '...') }}
                                               </p>
                                               
                                                <p><strong>Hiring From:</strong> {{ $job->hiring_from }}</p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-info w-100">Apply</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                            {{ $jobs->links() }} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <!-- Include DataTable script if necessary -->
@endsection

@section('scripts')
    <script>
        let defaultImageAvatar = "{{ getDefaultAvatar() }}";

        // Optional JavaScript for additional functionality
    </script>
@endsection
