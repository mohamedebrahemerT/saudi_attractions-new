<table class="table table-responsive" id="aboutuses-table">
    <thead>
        <tr>
            <th>Paragraph</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($about_uses as $aboutUs)
        <tr>
            <td>{!! $aboutUs->paragraph !!}</td>
            <td>
                <div class='btn-group'>
                    @foreach($languages as $language)
                        <a href="{!! route('about_uses.edit', [$aboutUs->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                         <a href="{!! route('about_uses.edit', [$aboutUs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>