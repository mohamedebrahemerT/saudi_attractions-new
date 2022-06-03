@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Contact Us</h1>
        <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ url('contact/export/csv') }}">Export CSV File</a>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('requests.contact_us.show_fields')
            </div>
        </div>
    </div>
@endsection

