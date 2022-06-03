<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', "Attraction Title", ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', 'Description', ['class' => 'form-control', 'style' => 'height:110px;', 'required' => 'required']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', 'Address', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Address URL Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_url', 'Address URL:') !!}
    {!! Form::url('address_url', "https://goo.gl/maps/MdMxi6SDFZoYGScTA", ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', 'Lat:') !!}
    {!! Form::number('lat', '000000000', ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'step' => 'any']) !!}
</div>

<!-- Lng Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lng', 'Lng:') !!}
    {!! Form::number('lng', '000000000', ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'step' => 'any']) !!}
</div>

<!-- Is Sponsored Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_sponsored', 'Sponsored:') !!}
    {!! Form::checkbox('is_sponsored', 1)!!}
</div>

<!-- Is Featured Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_featured', 'Featured:') !!}
    {!! Form::checkbox('is_featured', 1)!!}
</div>

<!-- Weekly Suggestions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('week_suggest', 'Weekly Suggestions:') !!}
    {!! Form::checkbox('week_suggest', 1)!!}
</div>

<!-- Is Featured Field -->
<div class="form-group col-sm-6">
    {!! Form::label('national_id', 'Allow National ID:') !!}
    {!! Form::checkbox('national_id', 1)!!}
</div>

<div class="form-group col-sm-12">
    <strong><h3>Contact Numbers</h3></strong>
</div>

<div class="contact_numbers">
    <div class="form-group col-sm-6">
        {!! Form::label('contact_numbers', 'Contact Numbers:') !!}<br>
        {!! Form::text('contact_numbers[]',null, ['class' => 'form-control']) !!}
    </div>
    <!-- Telephone Field -->
    <div class="form-group col-sm-6">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_contact_numbers" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>

<div class="form-group col-sm-12">
    <strong><h3>Ticket Types</h3></strong>
</div>

<div class="tickets">
    <div class="form-group col-sm-3">
        {!! Form::label('type', 'Type:') !!}<br>
        <input type="text" value="Type" name="attraction_tickets[0][type]" class="form-control" required>
        <!-- {!! Form::text('attraction_tickets[0][type]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('description', 'Description:') !!}<br>
        <input type="text" value="description" name="attraction_tickets[0][description]" class="form-control" required>
        <!-- {!! Form::text('attraction_tickets[0][description]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('price', 'Price:') !!}<br>
        <input type="number" value="155" name="attraction_tickets[0][price]" min="0" step="any" class="form-control" required>
        <!-- {!! Form::text( 'attraction_tickets[0][price]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
        <input type="number" value="5" name="attraction_tickets[0][number_of_tickets]" min="0" class="form-control" required>
        <!-- {!! Form::number( 'attraction_tickets[0][number_of_tickets]') !!} -->
    </div>
    <!-- Telephone Field -->
    <div class="form-group col-sm-12">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_tickets" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>


<div class="form-group col-sm-12">
    <strong><h3>Ticket Add-ons</h3></strong>
</div>

<div class="add_ons">
    <div class="form-group col-sm-3">
        {!! Form::label('name', 'Name:') !!}<br>
        <input type="text" value="Name" name="attraction_addons[0][name]" class="form-control" required>
        <!-- {!! Form::text('attraction_addons[0][name]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('description', 'Description:') !!}<br>
        <input type="text" name="attraction_addons[0][description]" class="form-control" value="description" required>
        <!-- {!! Form::text('attraction_addons[0][description]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('price', 'Price:') !!}<br>
        <input type="number" value="10" name="attraction_addons[0][price]" min="0" step="any" class="form-control" required>
        <!-- {!! Form::text('attraction_addons[0][price]') !!} -->
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
        <input type="number" name="attraction_addons[0][number_of_tickets]" min="0" class="form-control" value="4" required>
        <!-- {!! Form::number('attraction_addons[0][number_of_tickets]') !!} -->
    </div>
    <!-- Telephone Field -->
    <div class="form-group col-sm-12">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_add_ons" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>

<!-- Default Image Field -->
<div class="form-group col-sm-12">
    {!! Form::label('image', 'Default Image:') !!}
    {!! Form::file('image', ['required' => 'required']) !!}
</div>

<!-- Gallery Field -->
<div class="gallery">
    <div class="form-group col-sm-6">
        {!! Form::label('gallery', 'Gallery:') !!}
        {!! Form::file('gallery[]')!!}
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
            @if($category->attractions->contains((isset($category_attraction) ? $category_attraction->id : 0)))
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
            @if($sub_category->attractions->contains((isset($sub_category_attraction) ? $sub_category_attraction->id : 0)))
                <option value="{{$sub_category->id}}" selected>{{$sub_category->name}}</option>
            @else
                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12">
    <strong><h3>Attraction Tags</h3></strong>
</div>

<div class="tags">
    <div class="form-group col-sm-4">
        {!! Form::label('name', 'Name:') !!}<br>
        <input type="text" name="tags[0][name]" value="attraction name " required class="form-control">
        <!-- {!! Form::text('tags[0][name]') !!} -->
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('image', 'Icon:') !!}<br>
        {!! Form::file('tags[0][image]') !!}
    </div>

    <!-- Telephone Field -->
    <div class="form-group col-sm-4">
        <br>
        <a style="height: 40px;"  href="#" id="add_more_tags" class='btn btn-default'><strong>Add More </strong></a>
    </div>
</div>


<!-- Number of days that are going to be Viewed From today Field -->
<div class="form-group col-sm-6">
    {!! Form::label('number_of_days', 'Number of days that are going to be Viewed From today:') !!}
    {!! Form::number('number_of_days', '1', ['class' => 'form-control', 'required' => 'required', 'min' => '0']) !!}
</div>

<div class="form-group col-sm-12">
    <strong><h3>Payment Methods</h3></strong>
</div>

<!-- Credit Card Field -->
<div class="form-group col-sm-3">
    {!! Form::label('credit_card', 'Allow Credit Card Method:') !!}
    {!! Form::checkbox('credit_card', 1)!!}
</div>

<!-- Cash On Delivery Field -->
<div class="form-group col-sm-3">
    {!! Form::label('cash_on_delivery', 'Allow Cash On Delivery Method:') !!}
    {!! Form::checkbox('cash_on_delivery', 1)!!}
</div>

<!-- Pay Later Field -->
<div class="form-group col-sm-3">
    {!! Form::label('pay_later', 'Allow Pay Later Method:') !!}
    {!! Form::checkbox('pay_later', 1)!!}
</div>

<!-- Free Ticket Field -->
<div class="form-group col-sm-3">
    {!! Form::label('free', 'Allow Free Tickets:') !!}
    {!! Form::checkbox('free', 1)!!}
</div>

<!-- Max Number of Cash On Delivery Tickets Field -->
<div class="form-group col-sm-4">
    {!! Form::label('max_of_cash_tickets', 'Max Number of Cash On Delivery Tickets:') !!}
    {!! Form::number('max_of_cash_tickets', null, ['class' => 'form-control', 'min' => '0']) !!}
</div>

<!-- Max Number of Pay Later Tickets Field -->
<div class="form-group col-sm-4">
    {!! Form::label('max_of_pay_later_tickets', 'Max Number of Pay Later Tickets:') !!}
    {!! Form::number('max_of_pay_later_tickets', null, ['class' => 'form-control', 'min' => '0']) !!}
</div>

<!-- Max Number of Free Tickets Field -->
<div class="form-group col-sm-4">
    {!! Form::label('max_of_free_tickets', 'Max Number of Free Tickets:') !!}
    {!! Form::number('max_of_free_tickets', null, ['class' => 'form-control', 'min' => '0']) !!}
</div>


<div class="form-group col-sm-12">
    <strong><h3>Attraction Social Media</h3></strong>
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
    <strong><h3>Attraction Notification</h3></strong>
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
    {!! Form::submit('Continue', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('attractions.index') !!}" class="btn btn-default">Cancel</a>
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
    $("#add_more_contact_numbers").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.contact_numbers').append("<div class='form-group col-sm-6 "+id+"'><input type='text' name='contact_numbers[]' class='form-control'></div> " +
            "<div class='form-group col-sm-6 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".contact_numbers").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>



<script>
    $("#add_more_tickets").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.tickets').append("<div class='form-group col-sm-3 "+id+"'><input type='text' name='attraction_tickets["+id+"][type]' class='form-control' required></div> " +
            "<div class='form-group col-sm-3 "+id+"'><input type='text' name='attraction_tickets["+id+"][description]' class='form-control' required></div>" +
            "<div class='form-group col-sm-3 "+id+"'><input type='number' name='attraction_tickets["+id+"][price]' min='0' class='form-control' step='any' required></div> " +
            "<div class='form-group col-sm-3 "+id+"'><input type='number' name='attraction_tickets["+id+"][number_of_tickets]' min='0' class='form-control' required></div>" +
            "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".tickets").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>


<script>
    $("#add_more_add_ons").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.add_ons').append("<div class='form-group col-sm-3 "+id+"'><input type='text' name='attraction_addons["+id+"][name]' required class='form-control'></div> " +
            "<div class='form-group col-sm-3 "+id+"'><input type='text' name='attraction_addons["+id+"][description]' required class='form-control'></div>" +
            "<div class='form-group col-sm-3 "+id+"'><input type='number' name='attraction_addons["+id+"][price]' required class='form-control' min='0'></div> " +
            "<div class='form-group col-sm-3 "+id+"'><input type='number' name='attraction_addons["+id+"][number_of_tickets]' required class='form-control' min='0'></div>" +
            "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".add_ons").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>

<script>
    $("#add_more_tags").click(function (e) {
        e.preventDefault();
        var id=uniqId();
        $('.tags').append("<div class='form-group col-sm-4 "+id+"'><input type='text' name='tags["+id+"][name]' class='form-control'></div> " +
            "<div class='form-group col-sm-4 "+id+"'><input type='file' name='tags["+id+"][image]'></div>" +
            "<div class='form-group col-sm-4 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
    })
    $(".tags").on('click','.minus',(function (e) {
        e.preventDefault();
        var id=$(this).attr('id');
        $('.'+id).remove();
    }))
    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }
</script>