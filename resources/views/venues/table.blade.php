<table class="table table-responsive" id="venues-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Address</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($venues as $venue)
        <tr>
            <td>{!! $venue->title !!}</td>
            <td>{!! $venue->address !!}</td>
            <td>
                {!! Form::open(['route' => ['venues.destroy', $venue->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @if(Auth::user()->role->roles_permission->contains($permission['venues.publish']))
                        @if($venue->draft == 0)
                            <a href="{!! route('venues.publish',[$venue->id]) !!}" class='btn btn-default btn-xs'>Publish</a>
                        @else
                            <p class='btn btn-success btn-xs'>Published</p>
                        @endif
                    @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['venues.editable']))
                            @if($venue->is_editable == 0)
                            <a href="{!! route('venues.editable',[$venue->id]) !!}" class='btn btn-default btn-xs'>Send To User</a>
                            @else
                                <p class='btn btn-success btn-xs'>Sent</p>
                            @endif
                        @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['venues.notification']))
                             <a href="{!! route('venues.notification', [$venue->id]) !!}" class='btn btn-default btn-xs'>Push Notification</a>
                        @endif
                    @foreach($languages as $language)
                        <a href="{!! route('venues.edit', [$venue->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                    <a href="{!! route('venues.edit', [$venue->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        @if(Auth::user()->role->roles_permission->contains($permission['venues.destroy']))
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>