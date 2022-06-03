
<table class="table table-responsive" id="cities-table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mobile Number</th>
        <th>Attraction</th>
        <th>Payment Method</th>
        <th>Order Number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{!! $order->id !!}</td>
            <td>{!! $order->name !!}</td>
            <td>{!! $order->mobile_number !!}</td>
            <td>{!! $order->attraction['title'] !!}</td>
            <td>{!! $order->payment_method !!}</td>
            <td>{!! $order->order_number !!}</td>
            <td>
                @if($order->payment_method != 'credit_card')
                    <div class='btn-group'>
                        @if($order->status==1)
                            <a href="{!! route('attraction_orders.reject', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ban-circle"></i></a>
                        @else
                            <a href="{!! route('attraction_orders.approve', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-check"></i></a>
                        @endif
                    </div>
                @else
                    <p>{{ Config::get('order_status.'. $order->status)}}</p>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>