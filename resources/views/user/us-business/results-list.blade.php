@extends('layouts.user')

@section('shell_class', 'user-shell-fluid')
@section('title', 'Search Results - List')

@section('content')
    @include('user.us-business.partials.results-header')
    @include('user.us-business.partials.results-table')
    @include('user.us-business.partials.results-modals')
@endsection