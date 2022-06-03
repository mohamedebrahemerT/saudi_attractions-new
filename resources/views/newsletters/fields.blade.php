<!-- Paragraph Field -->
<div class="form-group col-sm-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'froala-editor']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('newsletters.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script>
    $(function() {
        $('textarea#froala-editor').froalaEditor()
    });
</script>