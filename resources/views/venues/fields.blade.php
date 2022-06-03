<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', 'Venue Title', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', 'Description', ['class' => 'form-control', 'style' => 'height:110px;', 'required' => 'required']) !!}
</div>


<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', 'address', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('location', 'Location:') !!}
    {!! Form::url('location', 'https://goo.gl/maps/MdMxi6SDFZoYGScTA', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Website Field -->
<div class="form-group col-sm-6">
    {!! Form::label('website', 'Website:') !!}
    {!! Form::url('website', null, ['class' => 'form-control']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', 'Lat:') !!}
    {!! Form::number('lat', '00000000000', ['class' => 'form-control','min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
</div>

<!-- Lng Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lng', 'Lng:') !!}
    {!! Form::number('lng', '00000000000', ['class' => 'form-control','min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
</div>

<!-- Is Sponsored Field -->
<div class="form-group col-sm-3">
    {!! Form::label('is_sponsored', 'Sponsored:') !!}
    {!! Form::checkbox('is_sponsored', 1)!!}
</div>

<!-- Is Featured Field -->
<div class="form-group col-sm-3">
    {!! Form::label('is_featured', 'Featured:') !!}
    {!! Form::checkbox('is_featured', 1)!!}
</div>

<!-- Is Brand Field -->
<div class="form-group col-sm-3">
    {!! Form::label('is_brand', 'Jeddawi Brand:') !!}
    {!! Form::checkbox('is_brand', 1)!!}
</div>

<!-- Weekly Suggestions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('week_suggest', 'Weekly Suggestions:') !!}
    {!! Form::checkbox('week_suggest', 1)!!}
</div>

<div class="form-group col-sm-12">
    <strong><h3>Telephone Numbers</h3></strong>
</div>

<div class="telephone_numbers">
    <div class="form-group col-sm-6">
        {!! Form::label('telephone_numbers', 'Telephone Numbers:') !!}<br>
        <input type="text" name="telephone_numbers[]" value="0999999999999" class="form-control">
        <!-- {!! Form::text('telephone_numbers[]') !!} -->
    </div>
    <!-- Telephone Field -->
    <div class="form-group col-sm-6">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_telephone_numbers" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>

<div class="form-group col-sm-12">
    <strong><h3>Opening Hours</h3></strong>
</div>

        @foreach($days as $key => $day)
            <div class="form-group col-sm-3">
                <strong><h4> {{ $day->day  }} </h4></strong>

            </div>
            <div class="form-group col-sm-3">
                {!! Form::label('start_time', 'Start Time:') !!}<br>
                <input type="time" name="venue_opening_hours[{{$key}}][start_time]" class="form-control">
            </div>
            <div class="form-group col-sm-3">
                {!! Form::label('end_time', 'End Time:') !!}<br>
                <input type="time" name="venue_opening_hours[{{$key}}][end_time]" class="form-control">
            </div>
            <div class="form-group col-sm-3">
                {!! Form::label('is_closed', 'Closed:') !!}
                {!! Form::checkbox('venue_opening_hours['.$key.'][is_closed]', 1)!!}
            </div>
            <div class="form-group col-sm-12">
                {!! Form::hidden('venue_opening_hours['.$key.'][venue_day_id]', $day->id) !!}
            </div>
        @endforeach


<!-- Default Image Field -->
<div class="form-group col-sm-12">
    {!! Form::label('image', 'Default Image:') !!}
    {!! Form::file('image' , ['required' => 'required']) !!}
</div>

<!-- Gallery Field -->
<div class="gallery">
    <div class="form-group col-sm-6">
        {!! Form::label('gallery', 'Gallery:') !!}
        {!! Form::file('gallery[]', ['required' => 'required'])!!}
    </div>
    <!-- Telephone Field -->
    <div class="form-group col-sm-6">
        <br>
        <a style="height: 40px;"  href="#" id="add_more" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>

<!-- Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category', 'Categories:') !!}<br>
    <select name="categories[]" class="form-control select2"  multiple>
        @foreach($categories as $category)
            @if($category->venues->contains((isset($category_venue) ? $category_venue->id : 0)))
                <option value="{{$category->id}}" selected>{{$category->name}}</option>
            @else
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endif
        @endforeach
    </select>
</div>

<!-- Sub Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('subcategory', 'Sub Categories:') !!}<br>
    <select name="sub_categories[]" class="form-control select2" multiple>
        @foreach($sub_categories as $sub_category)
            @if($sub_category->venues->contains((isset($sub_category_venue) ? $sub_category_venue->id : 0)))
                <option value="{{$sub_category->id}}" selected>{{$sub_category->name}}</option>
            @else
                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12">
    <strong><h3>Venue Social Media</h3></strong>
</div>

<div class="social_media">
    @foreach($social_media as $social)
        <div class="form-group col-sm-6">
            {!! Form::label('social_media_url', ''.$social['name'].'') !!}<br>
            {!! Form::label('social_url', 'URL') !!}<br>
            {!! Form::url('social_media['.$social['id'].'][url]',null,  ['class'=>'form-control'] ) !!}
            {!! Form::label('social_name', 'Name') !!}<br>
            {!! Form::text('social_media['.$social['id']. '][name]',null,  ['class'=>'form-control'] ) !!}
        </div><br>
    @endforeach
</div>

<div class="form-group col-sm-12">
    <strong><h3>Venue Notification</h3></strong>
</div>


<!-- Arabic Notification Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arabic_notification_title', 'Arabic Notification Title:') !!}
    {!! Form::text('arabic_notification_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Arabic Notification Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arabic_notification_description', 'Arabic Notification Description:') !!}
    {!! Form::text('arabic_notification_description', null, ['class' => 'form-control']) !!}
</div>

<!-- English Notification Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('english_notification_title', 'English Notification Title:') !!}
    {!! Form::text('english_notification_title', null, ['class' => 'form-control']) !!}
</div>

<!-- English Notification Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('english_notification_description', 'English Notification Description:') !!}
    {!! Form::text('english_notification_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('venues.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $("#add_more").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.gallery').append("<div class='form-group col-sm-6 "+id+"'><input type='file' name='gallery[]'></div> <div class='form-group col-sm-6 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div>");
    })
    $(".gallery").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>

<script>
    $("#add_more_telephone_numbers").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.telephone_numbers').append("<div class='form-group col-sm-6 "+id+"'><input type='text' class='form-control' name='telephone_numbers[]'></div> " +
            "<div class='form-group col-sm-6 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".telephone_numbers").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>