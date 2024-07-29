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
                                    {{ __('Edit Job') }}
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
        <form action="{{ route('jobs.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="job_post">JOB POST:</label>
                <input type="text" name="job_post" class="form-control" placeholder="JOB POST" value="{{ $job->job_post }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $job->email }}">
            </div>
            <div class="form-group">
                <label for="company_name">COMPANY NAME:</label>
                <input type="text" name="company_name" class="form-control" placeholder="COMPANY NAME" value="{{ $job->company_name }}">
            </div>
            <fieldset>
                <legend>JOB TYPE:</legend>
                @foreach(['internship', 'work_from_home', 'part_time', 'full_time'] as $type)
                    <label>
                        <input type="checkbox" name="job_type[]" value="{{ $type }}" 
                               {{ in_array($type, json_decode($job->job_type)) ? 'checked' : '' }}> {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </label><br>
                @endforeach
            </fieldset>
            <div class="form-group">
                <label for="doj">DURATION OF JOB:</label>
                <input type="text" name="doj" class="form-control" placeholder="DURATION OF JOB" value="{{ $job->doj }}">
            </div>
            <div class="form-group">
                <label for="apply_by">APPLY BY:</label>
                <input type="date" name="apply_by" class="form-control" placeholder="APPLY BY" value="{{ $job->apply_by }}">
            </div>
            <div class="form-group">
                <label for="salary">STIPEND/SALARY:</label>
                <input type="text" name="salary" class="form-control" placeholder="STIPEND/SALARY" value="{{ $job->salary }}">
            </div>
            <div class="form-group">
                <label for="hiring_from">HIRING FROM:</label>
                <input type="text" name="hiring_from" class="form-control" placeholder="HIRING FROM" value="{{ $job->hiring_from }}">
            </div>
            <div class="form-group">
                <label for="about_company">ABOUT THE COMPANY:</label>
                <textarea name="about_company" class="form-control" placeholder="ABOUT THE COMPANY">{{ $job->about_company }}</textarea>
            </div>
            <div class="form-group">
                <label for="about_job">ABOUT THE JOB:</label>
                <textarea name="about_job" class="form-control" placeholder="ABOUT THE JOB">{{ $job->about_job }}</textarea>
            </div>
            <div class="form-group">
                <label for="who_can_apply">WHO CAN APPLY:</label>
                <input type="text" name="who_can_apply" class="form-control" placeholder="WHO CAN APPLY" value="{{ $job->who_can_apply }}">
            </div>
            <div class="form-group">
                <label for="skill_required">SKILL REQUIRED:</label>
                <input type="text" name="skill_required" class="form-control" placeholder="SKILL REQUIRED" value="{{ $job->skill_required }}">
            </div>
            <div class="form-group">
                <label for="add_perks_of_job">ADD PERKS OF THE JOB:</label>
                <input type="text" name="add_perks_of_job" class="form-control" placeholder="ADD PERKS OF THE JOB" value="{{ $job->add_perks_of_job }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
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

