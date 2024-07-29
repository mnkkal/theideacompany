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
                                    {{ __('Job Details') }}
                                </div>
                                
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                <a href="{{ route('jobs.index') }}" class="btn btn-secondary mb-3">Back to Jobs</a>
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                  
                                </div>
                              
                            </div>
                        </div>
                        <div class="card-body">
                <h5 class="card-title">{{ $job->job_post }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ $job->email }}</p>
                <p class="card-text"><strong>Company Name:</strong> {{ $job->company_name }}</p>
                <p class="card-text"><strong>Job Type:</strong> 
                    @foreach(json_decode($job->job_type) as $type)
                        <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                    @endforeach
                </p>
                <p class="card-text"><strong>Duration of Job:</strong> {{ $job->doj }}</p>
                <p class="card-text"><strong>Apply By:</strong> {{ $job->apply_by }}</p>
                <p class="card-text"><strong>Salary:</strong> {{ $job->salary }}</p>
                <p class="card-text"><strong>Hiring From:</strong> {{ $job->hiring_from }}</p>
                <p class="card-text"><strong>About Company:</strong> {{ $job->about_company }}</p>
                <p class="card-text"><strong>About Job:</strong> {{ $job->about_job }}</p>
                <p class="card-text"><strong>Who Can Apply:</strong> {{ $job->who_can_apply }}</p>
                <p class="card-text"><strong>Skill Required:</strong> {{ $job->skill_required }}</p>
                <p class="card-text"><strong>Add Perks of Job:</strong> {{ $job->add_perks_of_job }}</p>
                <a href="{{ route('job-applications.apply', $job->id) }}" class="btn btn-primary mt-3">Apply</a>
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

