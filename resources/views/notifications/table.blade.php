<table class="table table-responsive" id="notifications-table">
    <thead>
        <tr>
            <th>English Title</th>
            <th>English Description</th>
            <th>Arabic Title</th>
            <th>Arabic Description</th>
        </tr>
    </thead>
    <tbody>
    @foreach($notifications as $notification)
        <tr>
            <td>{!! $notification->english_title !!}</td>
            <td>{!! $notification->english_description !!}</td>
            <td>{!! $notification->arabic_title !!}</td>
            <td>{!! $notification->arabic_description !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>