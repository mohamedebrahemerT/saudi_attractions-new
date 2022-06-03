@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Orders
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">

                    <div class="form-group col-sm-12">
                        <strong><h3>Order:</h3></strong>
                    </div>

                    <!-- ID Field -->
                    <div class="form-group">
                        {!! Form::label('id', 'Order ID:') !!}
                        <p>{!! $order->id !!}</p>
                    </div>

                    <!-- Name Field -->
                    <div class="form-group">
                        {!! Form::label('name', ' User Name:') !!}
                        <p>{!! $order->name !!}</p>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        {!! Form::label('email', 'Email:') !!}
                        <p>{!! $order->email !!}</p>
                    </div>

                    <!-- Mobile Number Field -->
                    <div class="form-group">
                        {!! Form::label('mobile_number', 'Mobile Number:') !!}
                        <p>{!! $order->mobile_number !!}</p>
                    </div>

                    <!-- Payment Method Field -->
                    <div class="form-group">
                        {!! Form::label('payment_method', 'Payment Method:') !!}
                        <p>{!! $order->payment_method !!}</p>
                    </div>

                    <!-- Total Field -->
                    <div class="form-group">
                        {!! Form::label('total', 'Total:') !!}
                        <p>{!! $order->total !!}</p>
                    </div>

                    <!-- Order Number Field -->
                    <div class="form-group">
                        {!! Form::label('order_number', 'Order Number:') !!}
                        <p>{!! $order->payment_method !!}</p>
                    </div>

                    <!-- User National ID Field -->
                    <div class="form-group">
                        {!! Form::label('user_national_id', 'User National ID:') !!}
                        <p>{!! $order->user_national_id !!}</p>
                    </div>

                    <!-- Status Field -->
                    <div class="form-group">
                        {!! Form::label('status', 'Status:') !!}
                        <p>{!! $order->status !!}</p>
                    </div>

                    <!-- ID Field -->
                    <div class="form-group">
                        {!! Form::label('id', 'User ID:') !!}
                        <p>{!! $order->user['id'] !!}</p>
                    </div>

                    <!-- Name Field -->
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        <p>{!! $order->user['name'] !!}</p>
                    </div>

                    <!-- Username Field -->
                    <div class="form-group">
                        {!! Form::label('username', 'Username:') !!}
                        <p>{!! $order->user['username'] !!}</p>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        {!! Form::label('email', 'Email:') !!}
                        <p>{!! $order->user['email'] !!}</p>
                    </div>

                    <!-- Gender Field -->
                    <div class="form-group">
                        {!! Form::label('gender', 'Gender:') !!}
                        <p>{!! $order->user['gender'] !!}</p>
                    </div>

                    <!-- Date Of Birth Field -->
                    <div class="form-group">
                        {!! Form::label('birth_date', 'Date Of Birth:') !!}
                        <p>{!! $order->user['birth_date'] !!}</p>
                    </div>

                    <!-- Is Blocked Field -->
                    <div class="form-group">
                        {!! Form::label('is_blocked', 'Is Blocked:') !!}
                        <p>{!! $order->user['is_blocked'] !!}</p>
                    </div>

                    <!-- ID Field -->
                    <div class="form-group">
                        {!! Form::label('id', 'Attraction ID:') !!}
                        <p>{!! $order->attraction['id'] !!}</p>
                    </div>

                    <!-- Title Field -->
                    <div class="form-group">
                        {!! Form::label('title', 'Title:') !!}
                        <p>{!! $order->attraction['title'] !!}</p>
                    </div>

                    <!-- Address Field -->
                    <div class="form-group">
                        {!! Form::label('address', 'Address:') !!}
                        <p>{!! $order->attraction['address'] !!}</p>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group">
                        {!! Form::label('description', 'Description:') !!}
                        <p>{!! $order->attraction['description'] !!}</p>
                    </div>

                    <!-- Address URL Field -->
                    <div class="form-group">
                        {!! Form::label('address_url', 'Address URL:') !!}
                        <p>{!! $order->attraction['address_url'] !!}</p>
                    </div>

                    <!-- Lat Field -->
                    <div class="form-group">
                        {!! Form::label('lat', 'Latitude:') !!}
                        <p>{!! $order->attraction['lat'] !!}</p>
                    </div>

                    <!-- Is Featured Field -->
                    <div class="form-group">
                        {!! Form::label('is_featured', 'Is Featured:') !!}
                        <p>{!! $order->attraction['is_featured'] !!}</p>
                    </div>

                    <!-- Credit Card Field -->
                    <div class="form-group">
                        {!! Form::label('credit_card', 'Credit Card:') !!}
                        <p>{!! $order->attraction['credit_card'] !!}</p>
                    </div>

                    <!-- Pay Later Field -->
                    <div class="form-group">
                        {!! Form::label('pay_later', 'Pay Later:') !!}
                        <p>{!! $order->attraction['pay_later'] !!}</p>
                    </div>

                    <!-- Cash On Delivery Field -->
                    <div class="form-group">
                        {!! Form::label('cash_on_delivery', 'Cash On Delivery:') !!}
                        <p>{!! $order->attraction['cash_on_delivery'] !!}</p>
                    </div>

                    <!-- Max Of Cash Tickets Field -->
                    <div class="form-group">
                        {!! Form::label('max_of_cash_tickets', 'Maximum Of Cash Tickets:') !!}
                        <p>{!! $order->attraction['max_of_cash_tickets'] !!}</p>
                    </div>

                    <!-- Max Of Pay Later Field -->
                    <div class="form-group">
                        {!! Form::label('max_of_pay_later_tickets', 'Maximum Of Pay Later Tickets:') !!}
                        <p>{!! $order->attraction['max_of_pay_later_tickets'] !!}</p>
                    </div>

                    <!-- Number Of Likes Field -->
                    <div class="form-group">
                        {!! Form::label('number_of_likes', 'Number Of Likes:') !!}
                        <p>{!! $order->attraction['number_of_likes'] !!}</p>
                    </div>

                    @if($order->status==1)
                        <a href="{!! route('attraction_orders.approved_orders') !!}" class="btn btn-default">Back</a>
                        @if($order->is_verified==false)
                            <a href="{!! route('attraction_orders.verify', [$order->id]) !!}" class='btn btn-primary btn'>Verify</a>
                        @else
                            <a href="" class='btn btn-success'>Verified</a>
                        @endif
                        @else
                        <a href="{!! route('attraction_orders.index') !!}" class="btn btn-default">Back</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection