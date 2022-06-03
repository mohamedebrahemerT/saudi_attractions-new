@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Approved Orders</h1><br><br>

        <form action={{ route('orders.approved_show') }} class="form-group">
            <!-- Order Number Search -->
            <div class="form-group col-sm-3">
                {!! Form::label('order_number', 'Order Number:') !!}
                {!! Form::text('order_number', null, ['class' => 'form-control']) !!}
            </div>
                 {!! Form::submit('Search', ['class' => 'btn btn-primary pull-right','name'=>'search', 'style' => "margin-top: -10px;margin-bottom: 23px"]) !!}
        </form>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">

                <table class="table table-responsive" id="cities-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Event</th>
                        <th>Ticket Type</th>
                        <th>Number Of Tickets</th>
                        <th>Payment Method</th>
                        <th>Order Number</th>
                        <th>Verified</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{!! $order->id !!}</td>
                            <td>{!! $order->name !!}</td>
                            <td>{!! $order->mobile_number !!}</td>
                            <td>{!! $order->event['title'] !!}</td>
                            <td>{!! $order->event_ticket['type'] !!}</td>
                            <td>{!! $order->event_ticket['number_of_tickets'] !!}</td>
                            <td>{!! $order->payment_method !!}</td>
                            <td>{!! $order->order_number !!}</td>
                            <td>{!! $order->is_verified = strbool($order->is_verified) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{$orders->links()}}
@endsection

