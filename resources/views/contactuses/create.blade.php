@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contact Us
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'contactuses.store', 'files'=>true]) !!}

                        @include('contactuses.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
