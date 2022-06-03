@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Attraction
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($attraction, ['url' => route('attractions.update',[$attraction->id]).'?lang='.App::getLocale(), 'method' => 'patch','files'=>true]) !!}

                   <!-- Title Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('title', 'Title:') !!}
                           {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Description Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('description', 'Description:') !!}
                           {!! Form::textarea('description', null, ['class' => 'form-control', 'style' => 'height:110px;', 'required' => 'required']) !!}
                       </div>

                       <!-- Address Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('address', 'Address:') !!}
                           {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Address URL Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('address_url', 'Address URL:') !!}
                           {!! Form::url('address_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Lat Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('lat', 'Lat:') !!}
                           {!! Form::number('lat', null, ['class' => 'form-control', 'required' => 'required', 'min'=>'0', 'step'=>'any']) !!}
                       </div>

                       <!-- Lng Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('lng', 'Lng:') !!}
                           {!! Form::number('lng', null, ['class' => 'form-control', 'required' => 'required', 'min'=>'0', 'step'=>'any']) !!}
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
                           @foreach(explode(',', $attraction['contact_numbers']) as $key=>$number)
                               <div class="form-group col-sm-6 {{$key}}">
                                   {!! Form::text('contact_numbers[]', $number, ['class' => 'form-control']) !!}
                               </div>
                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-6 {{$key}}">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_contact_numbers" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-6 {{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif
                           @endforeach
                       </div>


                       <div class="form-group col-sm-12">
                           <strong><h3>Ticket Types</h3></strong>
                       </div>

                       <div class="tickets">
                           @foreach($attraction->attraction_tickets as $key=> $ticket_type)
                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('type', 'Type:') !!}<br>
                                   <input type="text" name="attraction_tickets[{{$key}}][type]" class='form-control' required value="{{$ticket_type->type}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('description', 'Description:') !!}<br>
                                   <input type="text" name="attraction_tickets[{{$key}}][description]" class='form-control' required value="{{$ticket_type->description}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('price', 'Price:') !!}<br>
                                   <input type="number" name="attraction_tickets[{{$key}}][price]" class='form-control' required min='0' step='any' value="{{$ticket_type->price}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   <input type="hidden" name="attraction_tickets[{{$key}}][id]" value="{{$ticket_type->id}}">
                                   {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
                                   <input type="number" name="attraction_tickets[{{$key}}][number_of_tickets]" class='form-control' required min='0' step='any' value="{{$ticket_type->number_of_tickets}}">
                               </div>

                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-12">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_tickets" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-12 {{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif

                           @endforeach
                       </div>


                       <div class="form-group col-sm-12">
                           <strong><h3>Ticket Add-ons</h3></strong>
                       </div>

                       <div class="add_ons">
                           @foreach($attraction->attraction_addons as $key=> $ticket_addon)
                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('name', 'Name:') !!}<br>
                                   <input type="text" name="attraction_addons[{{$key}}][name]" required class='form-control' value="{{$ticket_addon->name}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('description', 'Description:') !!}<br>
                                   <input type="text" name="attraction_addons[{{$key}}][description]" required class='form-control' value="{{$ticket_addon->description}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('price', 'Price:') !!}<br>
                                   <input type="number" name="attraction_addons[{{$key}}][price]" required min='0' step='any' class='form-control' value="{{$ticket_addon->price}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   <input type="hidden" name="attraction_addons[{{$key}}][id]" value="{{$ticket_addon->id}}">
                                   {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
                                   <input type="number" name="attraction_addons[{{$key}}][number_of_tickets]" class='form-control' required min='0' value="{{$ticket_addon->number_of_tickets}}">
                               </div>

                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-12">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_add_ons" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-12 {{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif

                           @endforeach
                       </div>




                       <!-- Default Image Field -->
                       <div class="form-group col-sm-12">
                           {!! Form::label('image', 'Default Image:') !!}<br>
                           <img src="{{asset($attraction->media->image)}}" height="50" width="50">
                           {!! Form::file('image') !!}
                       </div>

                       <!-- Gallery Field -->
                       <div class="gallery">
                           <div class="form-group col-sm-12">
                               {!! Form::label('gallery', 'Gallery:') !!}<br>
                           </div>
                           @foreach($attraction->gallery as $key=>$media)
                               <div class="form-group col-sm-6 {{$key}}">
                                   <img src="{{asset($media->image)}}" height="30" width="50">
                                   <input type="hidden" name="media[]" value="{{$media->id}}">
                               </div>
                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-6">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-6 {{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif

                           @endforeach
                       </div>

                       <!-- Category Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('category', 'Categories:') !!}<br>
                           <select name="categories[]" class="form-control select2" id="select2" multiple>
                               @foreach($categories as $category)
                                   @if($attraction->categories->contains((isset($category) ? $category->id : 0)))
                                       <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                   @else
                                       <option value="{{$category->id}}">{{$category->name}}</option>
                                   @endif
                               @endforeach
                           </select>
                       </div>

                       <!-- Sub Category Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('sub_category', 'Sub Categories:') !!}<br>
                           <select name="sub_categories[]" class="form-control select2" id="select2" multiple>
                               @foreach($sub_categories as $sub_category)
                                   @if($attraction->sub_categories->contains((isset($sub_category) ? $sub_category->id : 0)))
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
                           @foreach($attraction->tags as $key=> $tag)

                               <div class="form-group col-sm-4 tags-{{$key}}">
                                   {!! Form::label('name', 'Name:') !!}<br>
                                   <input type="text" name="tags[{{$key}}][name]" required class='form-control' value="{{$tag->name}}">
                               </div>

                               <div class="form-group col-sm-4 tags-{{$key}}">
                                   <img src="{{asset($tag->media->image)}}" height="30" width="50">
                                   <input type="file" name="tags[{{$key}}][image]" value="{{$tag->media->image}}">
                                   <input type="hidden" name="tags[{{$key}}][id]" value="{{$tag->id}}">
                               </div>
                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-12">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_tags" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-12 tags-{{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='tags-{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif

                           @endforeach
                       </div>


                       <!-- Number of days that are going to be Viewed From today Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('number_of_days', 'Number of days that are going to be Viewed From today:') !!}
                           {!! Form::number('number_of_days', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0']) !!}
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
                           @foreach($social_media as $key=> $social)
                               <div class="form-group col-sm-6">
                                   {!! Form::label('social_media', ''.$social['name']. ' : ') !!}<br>
                                   @if($attraction->social_media->contains((isset($social) ? $social['id'] : 0)))
                                       {!! Form::label('social_url', 'URL') !!}<br>
                                       {!! Form::url('social_media_inputs['.$social['id']. '][url]',$attraction->social_media()->find($social['id'])->pivot->url,['class'=>'form-control']) !!}
                                       {!! Form::label('social_name', 'Name') !!}<br>
                                       {!! Form::text('social_media_inputs['.$social['id']. '][name]',$attraction->social_media()->find($social['id'])->pivot->name,['class'=>'form-control']) !!}
                                   @else
                                       {!! Form::label('social_url', 'URL') !!}<br>
                                       {!! Form::url('social_media_inputs['.$social['id']. '][url]',null,['class'=>'form-control']) !!}
                                       {!! Form::label('social_name', 'Name') !!}<br>
                                       {!! Form::text('social_media_inputs['.$social['id']. '][name]',null,['class'=>'form-control']) !!}

                                   @endif

                               </div>
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


                       {!! Form::close() !!}

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
                               $('.contact_numbers').append("<div class='form-group col-sm-6 "+id+"'><input type='text' name='contact_numbers[]' class='form-control'></div> <div class='form-group col-sm-6 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
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
                           $("#add_more_exceptional").click(function (e) {
                               e.preventDefault();
                               var id=uniqId();
                               $('.exceptional').append("<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='date' name='attraction_exceptional_dates["+id+"][date]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='time' name='attraction_exceptional_dates["+id+"][start_time]'></div>" +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='time' name='attraction_exceptional_dates["+id+"][end_time]'></div> " +
                                   "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
                           })
                           $(".exceptional").on('click','.minus',(function (e) {
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
                               $('.tickets').append("<div class='form-group col-sm-3 "+id+"'><input class='form-control' required type='text' name='attraction_tickets["+id+"][type]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='text' required name='attraction_tickets["+id+"][description]'></div>" +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='number' required step='any' min='0' name='attraction_tickets["+id+"][price]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='number' required min='0' name='attraction_tickets["+id+"][number_of_tickets]'></div>" +
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
                               $('.add_ons').append("<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='text' required name='attraction_add_ons["+id+"][name]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='text' required name='attraction_add_ons["+id+"][description]'></div>" +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='number' required step='any' min='0' name='attraction_add_ons["+id+"][price]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input class='form-control' type='number' required min='0' name='attraction_add_ons["+id+"][number_of_tickets]'></div>" +
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
                               $('.tags').append("<div class='form-group col-sm-4 "+id+"'><input class='form-control' required type='text' name='tags["+id+"][name]'></div> " +
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
               </div>
           </div>
       </div>
   </div>
@endsection