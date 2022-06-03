@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Venue
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($venue, ['url' => route('venues.update',[$venue->id]).'?lang='.App::getLocale(), 'method' => 'patch','files'=>true]) !!}

                       <!-- Title Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('title', 'Title:') !!}
                           {!! Form::text('title', null, ['class' => 'form-control' , 'required' => 'required']) !!}
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

                       <!-- Location Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('location', 'Location:') !!}
                           {!! Form::url('location', null, ['class' => 'form-control', 'required' => 'required']) !!}
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
                           {!! Form::number('lat', null, ['class' => 'form-control', 'min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
                       </div>

                       <!-- Lng Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('lng', 'Lng:') !!}
                           {!! Form::number('lng', null, ['class' => 'form-control', 'min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
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
                           @foreach(explode(',', $venue['telephone_numbers']) as $key=>$number)
                               <div class="form-group col-sm-6 {{$key}}">
                                   {!! Form::text('telephone_numbers[]', $number, ['class' => 'form-control', 'required' => 'required']) !!}
                               </div>
                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-6 {{$key}}">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_telephone_numbers" class='btn btn-default'><strong>Add More </strong></a>
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
                           <strong><h3>Opening Hours</h3></strong>
                       </div>


                               @foreach($venue->venue_opening_hours as $key=> $hour)
                           <div class="form-group col-sm-12">
                               <strong><h4> {{ $hour->venue_days->day}} </h4></strong>
                           </div>
                                       <div class="form-group col-sm-4">
                                           {!! Form::label('start_time', 'Start Time:') !!}<br>
                                           {!! Form::time('venue_opening_hours['.$key.'][start_time]',$hour->start_time,['class'=>'form-control']) !!}
                                       </div>
                                       <div class="form-group col-sm-4">
                                            {!! Form::label('end_time', 'End Time:') !!}<br>
                                            {!! Form::time('venue_opening_hours['.$key.'][end_time]',$hour->end_time,['class'=>'form-control']) !!}
                                       </div>
                                       <div class="form-group col-sm-4">
                                            {!! Form::label('is_closed', 'Closed:') !!}<br>
                                            {!! Form::checkbox('venue_opening_hours['.$key.'][is_closed]',1,$hour->is_closed)!!}
                                       </div>
                                       <div class="form-group col-sm-3">
                                            {!! Form::hidden('venue_opening_hours['.$key.'][venue_day_id]',$hour->id,['class'=>'form-control']) !!}
                                   </div>
                               @endforeach

                       <!-- Default Image Field -->
                       <div class="form-group col-sm-6">
                           <img src="{{asset($venue->media->image)}}" height="50" width="50">
                           {!! Form::label('image', 'Default Image:') !!}
                           {!! Form::file('image') !!}
                       </div>

                       <!-- Gallery Field -->
                       <div class="gallery">
                           <div class="form-group col-sm-12">
                               {!! Form::label('gallery', 'Gallery:') !!}<br>
                           </div>
                           @foreach($venue->gallery as $key=>$media)
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
                                   @if($venue->categories->contains((isset($category) ? $category->id : 0)))
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
                                   @if($venue->sub_categories->contains((isset($sub_category) ? $sub_category->id : 0)))
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
                           @foreach($social_media as $key=> $social)
                               <div class="form-group col-sm-6">
                                   {!! Form::label('social_media', ''.$social['name']. ' : ') !!}<br>
                                   @if($venue->social_media->contains((isset($social) ? $social['id'] : 0)))
                                       {!! Form::label('social_url', 'URL') !!}<br>
                                       {!! Form::url('social_media_inputs['.$social['id']. '][url]',$venue->social_media()->find($social['id'])->pivot->url,['class'=>'form-control']) !!}
                                       {!! Form::label('social_name', 'Name') !!}<br>
                                       {!! Form::text('social_media_inputs['.$social['id']. '][name]',$venue->social_media()->find($social['id'])->pivot->name,['class'=>'form-control']) !!}
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



               </div>
           </div>
       </div>
   </div>
@endsection