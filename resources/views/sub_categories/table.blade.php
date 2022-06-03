<table class="table table-responsive" id="subCategories-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($subCategories as $subCategory)
        <tr>
            <td>{!! $subCategory->name !!}</td>
            <td>{!! isset($subCategory->category->name)? $subCategory->category->name:null!!}</td>
            <td>
                {!! Form::open(['route' => ['subCategories.destroy', $subCategory->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @foreach($languages as $language)
                        <a href="{!! route('subCategories.edit', [$subCategory->id]).'?lang='.$language->code !!}" class='btn btn-default btn-xs'>{{$language->name}}</a>
                    @endforeach
                    <a href="{!! route('subCategories.edit', [$subCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>

    @endforeach
    </tbody>
</table>