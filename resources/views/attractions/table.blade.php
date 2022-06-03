<table class="table table-responsive" id="attractions-table">
    <thead>
        <tr>
            <th>Title</th>
        <th>Address</th>
        <th>Description</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attractions as $attraction)
        <tr>
            <td>{!! $attraction->title !!}</td>
            <td>{!! $attraction->address !!}</td>
            <td>{!! $attraction->description !!}</td>
            <td>
                {!! Form::open(['route' => ['attractions.destroy', $attraction->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @if(Auth::user()->role->roles_permission->contains($permission['attractions.publish']))
                        @if($attraction->draft == 0)
                            <a href="{!! route('attractions.publish',[$attraction->id]) !!}" class='btn btn-default btn-xs'>Publish</a>
                        @else
                            <p class='btn btn-success btn-xs'>Published</p>
                        @endif
                    @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['attractions.editable']))
                            @if($attraction->is_editable == 0)
                                <a href="{!! route('attractions.editable',[$attraction->id]) !!}" class='btn btn-default btn-xs'>Send To User</a>
                            @else
                                <p class='btn btn-success btn-xs'>Sent</p>
                            @endif
                        @endif
                        @if(Auth::user()->role->roles_permission->contains($permission['attractions.notification']))
                            <a href="{!! route('attractions.notification', [$attraction->id]) !!}" class='btn btn-default btn-xs'>Push Notification</a>
                        @endif
                    @foreach($languages as $language)
                        <a href="{!! route('attractions.edit', [$attraction->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                    <a href="{!! route('attractions.edit', [$attraction->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        @if(Auth::user()->role->roles_permission->contains($permission['attractions.destroy']))
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>