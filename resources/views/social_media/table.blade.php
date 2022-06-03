<table class="table table-responsive" id="socialMedia-table">
    <thead>
        <tr>
            <th>Name</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($socialMedia as $socialMedia)
        <tr>
            <td>{!! $socialMedia->name !!}</td>
            <td>
                {!! Form::open(['route' => ['socialMedia.destroy', $socialMedia->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @foreach($languages as $language)
                        <a href="{!! route('socialMedia.edit', [$socialMedia->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                    <a href="{!! route('socialMedia.edit', [$socialMedia->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>