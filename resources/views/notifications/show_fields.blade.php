<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $notification->id !!}</p>
</div>

<!-- English Message Field -->
<div class="form-group">
    {!! Form::label('english_message', 'English Message:') !!}
    <p>{!! $notification->english_message !!}</p>
</div>

<!-- Arabic Message Field -->
<div class="form-group">
    {!! Form::label('arabic_message', 'Arabic Message:') !!}
    <p>{!! $notification->arabic_message !!}</p>
</div>

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'Url:') !!}
    <p>{!! $notification->url !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $notification->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $notification->updated_at !!}</p>
</div>

