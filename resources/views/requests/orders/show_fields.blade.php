
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
        <th>Created at</th>
        <th>Updated at</th>
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
            <td>{!! $order->created_at !!}</td>
            <td>{!! $order->updated_at !!}</td>
            <td>
                @if($order->payment_method != 'credit_card')
                    <div class='btn-group'>
                        @if($order->status==1)
                            <a href="{!! route('orders.reject', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ban-circle"></i></a>
                        @else
                            <a href="{!! route('orders.approve', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-check"></i></a>
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