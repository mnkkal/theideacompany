@extends('layouts.app')

@section('title')
    {{ __('Job Details') }}
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
                                    {{ __('Job Details') }}
                                </div>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <!-- Optional filters or actions -->
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $job->job_post }}</h5>
                            <p class="card-text"><strong>Email:</strong> {{ $job->email }}</p>
                            <p class="card-text"><strong>Company Name:</strong> {{ $job->company_name }}</p>
                            <div class="row">
                                <div class="col-3">
                                    <p><strong>Job Type:</strong></p>
                                    @foreach (json_decode($job->job_type) as $type)
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                                    @endforeach
                                </div>
                                <div class="col-3">
                                    <p class="mt-2"><strong>Apply By:</strong> {{ \Carbon\Carbon::parse($job->apply_by)->diffForHumans() }}</p>
                                </div>
                                <div class="col-3">
                                    <p><strong>Salary:</strong> {{ $job->salary }}</p>
                                </div>
                                <div class="col-3">
                                    <p class="card-text"><strong>Duration of Job:</strong> {{ $job->doj }}</p>
                                </div>
                            </div>
                            <p class="card-text"><strong>Hiring From:</strong> {{ $job->hiring_from }}</p>
                            <p class="card-text"><strong>About Company:</strong> {{ $job->about_company }}</p>
                            <p class="card-text"><strong>About Job:</strong> {{ $job->about_job }}</p>
                            <p class="card-text"><strong>Who Can Apply:</strong> {{ $job->who_can_apply }}</p>
                            <p class="card-text"><strong>Skill Required:</strong> {{ $job->skill_required }}</p>
                            <p class="card-text"><strong>Add Perks of Job:</strong> {{ $job->add_perks_of_job }}</p>
                            <a href="{{ route('job-applications.apply', $job->id) }}" class="btn btn-primary mt-3">Apply</a>
                        </div>
                    </div>

                    <!-- Check if the user has permission to manage job applications -->
                    @can('manage-job-applications')
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Job Applications</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Experience</th>
                                            <th>Current Job</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobApplications as $application)
                                            <tr>
                                                <td>{{ $application->name }}</td>
                                                <td>{{ $application->email }}</td>
                                                <td>{{ $application->phone }}</td>
                                                <td>{{ $application->experience }}</td>
                                                <td>{{ $application->current_job }}</td>
                                                <td>
                                                    <a href="{{ route('job-applications.show', $application->id) }}" class="btn btn-info btn-sm">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $jobApplications->links() }}
                                </div>
                            </div>
                        </div>
                    @endcan
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
