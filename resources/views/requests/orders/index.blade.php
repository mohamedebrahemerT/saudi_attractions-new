@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Orders</h1><br><br>

        <div class="form-group col-sm-12">
            <form action={{ route('orders.show') }} class="form-group">
                <!-- Order Number Search -->
                <div class="form-group col-sm-3">
                    {!! Form::label('order_number', 'Order Number:') !!}
                    {!! Form::text('order_number', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary pull-right','name'=>'search', 'style'=>'margin-top: 23px;']) !!}
                </div>
            </form>
        </div>
        <form action={{ route('orders.export', ['xls']) }} class="form-group">

            <!-- Event Title Filter -->
            <div class="form-group col-sm-2">
                {!! Form::label('title', 'Event Title:') !!}
                <select name="event_id" class="form-control">
                    <option value="">Select Option</option>
                    @foreach($event as $e)
                        <option value="{{ $e->id }}">{{ $e->title }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Start Date Filter -->
            <div class="form-group col-sm-2">
                {!! Form::label('start_date', 'Start Date:') !!}
                {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
            </div>
            <!-- End Date Filter -->
            <div class="form-group col-sm-2">
                {!! Form::label('end_date', 'End Date:') !!}
                {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Payment Method Filter -->
            <div class="form-group col-sm-2">
                {!! Form::label('payment_method', 'Payment Method:') !!}
                <select name="payment_method" class="form-control">
                    <option value="">Select Option</option>
                    @foreach($payment as $method)
                    <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-sm-2">
                {!! Form::label('status', 'Status:') !!}
                <select name="status" class="form-control">
                    <option value="">Select Option</option>
                    <option value="1">Approved Orders</option>
                    <option value="2">Rejected Orders</option>
                </select>
            </div>
            {!! Form::submit('Export', ['class' => 'btn btn-success pull-right','name'=>'export', 'value' => 'export', 'style' => "margin-top: 25px;margin-bottom: 5px"]) !!}
            {!! Form::submit('Filter', ['class' => 'btn btn-primary pull-right','name'=>'filter', 'value' => 'filter', 'style' => "margin-top: 25px;margin-bottom: 5px;margin-right: 10px;"]) !!}

        </form>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('requests.orders.show_fields')
            </div>
        </div>
    </div>
    {{$orders->links()}}
@endsection


