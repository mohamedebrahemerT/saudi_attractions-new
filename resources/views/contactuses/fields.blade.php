<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Telephone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Telephone:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Website Field -->
<div class="form-group col-sm-6">
    {!! Form::label('website', 'Website:') !!}
    {!! Form::text('website', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    <strong><h3>Social Platforms</h3></strong>
</div>

<div class="social">
    <div class="form-group col-sm-3">
        {!! Form::label('name', 'Name:') !!}<br>
        {!! Form::text('contactMedia[0][name]') !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('url', 'URL:') !!}<br>
        {!! Form::text('contactMedia[0][url]') !!}
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('image', 'Icon:') !!}<br>
        {!! Form::file('contactMedia[0][image]') !!}
    </div>

    <!-- Telephone Field -->
    <div class="form-group col-sm-3">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_media" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('contactuses.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    $("#add_more_media").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.social').append("<div class='form-group col-sm-3 "+id+"'><input type='text' name='contactMedia["+id+"][name]'></div> " +
        "<div class='form-group col-sm-3 "+id+"'><input type='text' name='contactMedia["+id+"][url]'></div> " +
            "<div class='form-group col-sm-3 "+id+"'><input type='file' name='contactMedia["+id+"][image]'></div>" +
           "<div class='form-group col-sm-3 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".social").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>