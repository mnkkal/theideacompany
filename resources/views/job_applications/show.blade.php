@extends('layouts.app')

@section('title')
    {{ __('Job Application Details') }}
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
            @include('flash::message')
            <div class="row justify-content-center">
                <div class="col-lg-10">
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
                                    <a href="{{ route('job-applications.index') }}" class="btn btn-secondary mb-3">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $jobApplication->job->title }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Name:</strong> {{ $jobApplication->name }}</p>
                                    <p class="card-text"><strong>Email:</strong> {{ $jobApplication->email }}</p>
                                    <p class="card-text"><strong>Phone:</strong> {{ $jobApplication->phone }}</p>
                                    <p class="card-text"><strong>Experience:</strong> {{ $jobApplication->experience }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text"><strong>Notice Period:</strong> {{ $jobApplication->notice_period }}</p>
                                    <p class="card-text"><strong>Current Job:</strong> {{ $jobApplication->current_job }}</p>
                                    <p class="card-text"><strong>Resume:</strong> <a href="{{ Storage::url($jobApplication->resume) }}" target="_blank">Download Resume</a></p>
                                </div>
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
