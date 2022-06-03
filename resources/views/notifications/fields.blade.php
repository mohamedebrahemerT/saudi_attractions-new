<!-- English Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('english_title', 'English Title:') !!}
    {!! Form::text('english_title', null, ['class' => 'form-control']) !!}
</div>

<!-- English Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('english_description', 'English Description:') !!}
    {!! Form::text('english_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Arabic Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arabic_title', 'Arabic Title:') !!}
    {!! Form::text('arabic_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Arabic Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arabic_description', 'Arabic Description:') !!}
    {!! Form::text('arabic_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type',$screens, null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('notifications.index') !!}" class="btn btn-default">Cancel</a>
</div>
