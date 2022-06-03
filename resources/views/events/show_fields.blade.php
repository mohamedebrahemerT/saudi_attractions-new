<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $event->id !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $event->title !!}</p>
</div>

<!-- Start Date Field -->
<div class="form-group">
    {!! Form::label('start_date', 'Start Date:') !!}
    <p>{!! $event->start_date !!}</p>
</div>

<!-- End Date Field -->
<div class="form-group">
    {!! Form::label('end_date', 'End Date:') !!}
    <p>{!! $event->end_date !!}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', 'Address:') !!}
    <p>{!! $event->address !!}</p>
</div>

<!-- Address Url Field -->
<div class="form-group">
    {!! Form::label('address_url', 'Address Url:') !!}
    <p>{!! $event->address_url !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $event->description !!}</p>
</div>

<!-- Start Price Field -->
<div class="form-group">
    {!! Form::label('start_price', 'Start Price:') !!}
    <p>{!! $event->start_price !!}</p>
</div>

<!-- End Price Field -->
<div class="form-group">
    {!! Form::label('end_price', 'End Price:') !!}
    <p>{!! $event->end_price !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $event->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $event->updated_at !!}</p>
</div>

