<table class="table table-responsive" id="events-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Address</th>
            <th>Start Price</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{!! $event->title !!}</td>
            <td>{!! date('d-m-Y', strtotime($event->start_date));!!}</td>
            <td>{!! date('d-m-Y', strtotime($event->end_date)); !!}</td>
            <td>{!! $event->address !!}</td>
            <td>{!! $event->start_price !!}</td>
            <td>

                {!! Form::open(['route' => ['events.destroy', $event->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @if(Auth::user()->role->roles_permission->contains($permission['events.publish']))
                        @if($event->draft == 0)
                            <a href="{!! route('events.publish',[$event->id]) !!}" class='btn btn-default btn-xs'>Publish</a>
                        @else
                            <p class='btn btn-success btn-xs'>Published</p>
                        @endif
                    @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['events.editable']))
                            @if($event->is_editable == 0)
                                <a href="{!! route('events.editable',[$event->id]) !!}" class='btn btn-default btn-xs'>Send To User</a>
                            @else
                                <p class='btn btn-success btn-xs'>Sent</p>
                            @endif
                        @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['events.notification']))
                            <a href="{!! route('events.notification', [$event->id]) !!}" class='btn btn-default btn-xs'>Push Notification</a>
                        @endif
                            @foreach($languages as $language)
                        <a href="{!! route('events.edit', [$event->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                    <a href="{!! route('events.edit', [$event->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        @if(Auth::user()->role->roles_permission->contains($permission['events.destroy']))
                             {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>