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
                        <div class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('Investors') }}
                                </div>
                                <a href="{{ route('investors.create') }}">
                                    <button type="button" class="my-2 pull-right btn btn-primary filter-container__btn ms-sm-0 ms-auto d-sm-none d-block">
                                        {{ __('New Investor') }}
                                    </button>
                                </a>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Additional filter content can be placed here -->
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Additional filter content can be placed here -->
                                </div>
                                <a href="{{ route('investors.create') }}">
                                    <button type="button" class="my-2 pull-right btn btn-primary new-user-btn filter-container__btn ms-sm-0 ms-auto">
                                        Create New Investor
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Problem/Opportunity</th>
                                        <th>Solution/Technology</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($investors as $investor)
                                    <tr>
                                        <td>{{ $investor->id }}</td>
                                        <td>{{ $investor->problem_opportunity }}</td>
                                        <td>{{ $investor->solution_technology }}</td>
                                        <td>
                                            <a href="{{ route('investors.show', $investor->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('investors.edit', $investor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('investors.destroy', $investor->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right me-3">
                                <!-- Pagination or other content can be placed here -->
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
