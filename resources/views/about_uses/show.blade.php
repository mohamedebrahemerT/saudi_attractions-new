@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            About Us
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('about_uses.show_fields')
                    <a href="{!! route('about_uses') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
