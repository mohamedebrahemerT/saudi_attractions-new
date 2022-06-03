
<table class="table table-responsive" id="cities-table">
    <thead>
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Subject</th>
        <th>Message</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contact_us as $contact)
        <tr>
            <td>{!! $contact->id !!}</td>
            <td>{!! $contact->user['name'] !!}</td>
            <td>{!! $contact->subject !!}</td>
            <td>{!! $contact->message !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>