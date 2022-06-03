<table class="table table-responsive" id="locales-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Code</th>
        </tr>
    </thead>
    <tbody>
    @foreach($locales as $locale)
        <tr>
            <td>{!! $locale->name !!}</td>
            <td>{!! $locale->code !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>