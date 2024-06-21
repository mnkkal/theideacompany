@extends('layouts.app')
@section('title')
    {{ __('messages.meetings') }}
@endsection
@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container meetings-container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header flex-wrap d-flex justify-content-end">
                            <div class="pull-left page__heading my-2 me-3">
                                {{ __('messages.meetings') }}
                            </div>
                            <div class="filter-container my-2 ms-auto">
                                <a href="{{ route('meetings.create') }}" class="pull-right btn btn-primary primary-btn">{{ __('messages.new_meeting') }}</a>
                            </div>
                            @if(isZoomTokenExpire())

                            <div class="filter-container margin-left-sm">
                                <a type="button" class="btn btn-success ml-3" href="{{route('zoom.connect')}}">
                                    {{ __('messages.new_keys.connect_with_zoom') }}
                                </a>
                            </div>
                        @endif
                        </div>
                        <div class="card-body">
                            @include('meetings.table')
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
    <script type="text/javascript" src="{{ asset('js/dataTable.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let defaultImageAvatar = "{{ getDefaultAvatar() }}"
    </script>
    <script src="{{ mix('assets/js/admin/meetings/meetings.js') }}"></script>
    <script src="{{ mix('assets/js/admin/meetings/meeting_index.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom-datatables.js') }}"></script>
@endsection

