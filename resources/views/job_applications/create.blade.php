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
                                {{ isset($jobApplication) ? 'Edit Application' : 'Apply for Job' }}
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
 
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ isset($jobApplication) ? route('job-applications.update', $jobApplication->id) : route('job-applications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($jobApplication))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="job_id">Job:</label>
            <select name="job_id" class="form-control">
                @foreach ($jobs as $job)
                    <option value="{{ $job->id }}" {{ isset($jobApplication) && $jobApplication->job_id == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $jobApplication->name ?? old('name') }}">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $jobApplication->email ?? old('email') }}">
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" class="form-control" value="{{ $jobApplication->phone ?? old('phone') }}">
        </div>
        <div class="form-group">
            <label for="experience">Experience:</label>
            <input type="text" name="experience" class="form-control" value="{{ $jobApplication->experience ?? old('experience') }}">
        </div>
        <div class="form-group">
            <label for="notice_period">Notice Period:</label>
            <input type="text" name="notice_period" class="form-control" value="{{ $jobApplication->notice_period ?? old('notice_period') }}">
        </div>
        <div class="form-group">
            <label for="current_job">Current Job:</label>
            <input type="text" name="current_job" class="form-control" value="{{ $jobApplication->current_job ?? old('current_job') }}">
        </div>
        <div class="form-group">
            <label for="resume">Resume:</label>
            <input type="file" name="resume" class="form-control">
            @if(isset($jobApplication) && $jobApplication->resume)
                <a href="{{ Storage::url($jobApplication->resume) }}" target="_blank">View Resume</a>
            @endif
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
        let defaultImageAvatar = "{{ getDefaultAvatar() }}"
    </script>
  
@endsection

