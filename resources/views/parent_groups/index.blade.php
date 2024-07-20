@extends('layouts.app')
@section('title')
    {{ __('Parent Groups') }}
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
                                    {{ __('Parent Groups') }}
                                </div>
                                <button type="button"
                                        class="my-2 pull-right btn btn-primary filter-container__btn ms-sm-0 ms-auto d-sm-none d-block"
                                        data-bs-toggle="modal"
                                        data-bs-target="#create_parent_group_modal">{{ __('New Parent Group') }}</button>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Any additional filters can go here -->
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Any additional filters can go here -->
                                </div>
                               <a href="{{ route('parent-groups.create') }}"> <button type="button"
                                        class="my-2 pull-right btn btn-primary new-user-btn filter-container__btn ms-sm-0 ms-auto"
                                        >{{ __('New Parent Group') }}</button></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('parent_groups.table')
                            <div class="pull-right me-3">
                                <!-- Pagination or additional controls can go here -->
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
