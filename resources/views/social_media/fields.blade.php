<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    @if(isset($socialMedia))
        <img src="{{asset($socialMedia->media->image)}}" height="50" width="50">
    @endif
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image') !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('socialMedia.index') !!}" class="btn btn-default">Cancel</a>
</div>
