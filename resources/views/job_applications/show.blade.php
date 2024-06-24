@extends('layouts.app')
@section('title')
    {{ __('Jobs') }}
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
                                    {{ __('Job Application Details') }}
                                </div>
                                
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                  
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                  
                                </div>
                              
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="container">
  
    <div>
        <strong>Job Title:</strong>
        {{ $jobApplication->job->title }}
    </div>
    <div>
        <strong>Name:</strong>
        {{ $jobApplication->name }}
    </div>
    <div>
        <strong>Email:</strong>
        {{ $jobApplication->email }}
    </div>
    <div>
        <strong>Phone:</strong>
        {{ $jobApplication->phone }}
    </div>
    <div>
        <strong>Experience:</strong>
        {{ $jobApplication->experience }}
    </div>
    <div>
        <strong>Notice Period:</strong>
        {{ $jobApplication->notice_period }}
    </div>
    <div>
        <strong>Current Job:</strong>
        {{ $jobApplication->current_job }}
    </div>
    <div>
        <strong>Resume:</strong>
        <a href="{{ Storage::url($jobApplication->resume) }}" target="_blank">Download Resume</a>
    </div>
    <a href="{{ route('job-applications.index') }}" class="btn btn-primary">Back</a>
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

