@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Event
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($event, ['url' => route('events.update',[$event->id]).'?lang='.App::getLocale(), 'method' => 'patch', 'files'=>true]) !!}

                   <!-- Title Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('title', 'Title:') !!}
                           {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Start Date Field -->
                        <div class="form-group col-sm-6 datepicker-container">
                            {!! Form::label('start_date', 'Start Date:') !!}
                            <!-- {!! Form::text('start_date', null, ['class' => 'form-control', 'data-language' => 'en', 'readonly' => 'readonly', 'data-date-format' => 'yyyy-mm-dd']) !!} -->
                            <input type="text" name="start_date" value="{{$event->start_date}}" class='form-control datepicker-here' required data-language='en' readonly="readonly" data-date-format="yyyy-mm-dd">
                        </div>

                        <!-- End Date Field -->
                        <div class="form-group col-sm-6 datepicker-container">
                            {!! Form::label('end_date', 'End Date:') !!}
                            <!-- {!! Form::text('end_date', null, ['class' => 'form-control', 'data-language' => 'en', 'readonly' => 'readonly', 'data-date-format' => 'yyyy-mm-dd']) !!} -->
                            <input type="text" name="end_date" value="{{$event->end_date}}" class='form-control datepicker-here'  data-language='en' readonly="readonly" data-date-format="yyyy-mm-dd">
                        </div>

                       <!-- Address Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('address', 'Address:') !!}
                           {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Address Url Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('address_url', 'Address URL:') !!}
                           {!! Form::url('address_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
                       </div>

                       <!-- Lat Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('lat', 'Lat:') !!}
                           {!! Form::number('lat', null, ['class' => 'form-control','min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
                       </div>

                       <!-- Lng Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('lng', 'Lng:') !!}
                           {!! Form::number('lng', null, ['class' => 'form-control','min'=>'0', 'required' => 'required', 'step' => 'any']) !!}
                       </div>

                       <!-- Description Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('description', 'Description:') !!}
                           {!! Form::text('description', null, ['class' => 'form-control', 'style' => 'rows:10;', 'required' => 'required']) !!}
                       </div>

                       <!-- Start Price Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('start_price', 'Start Price:') !!}
                           {!! Form::number('start_price', null, ['class' => 'form-control', 'required' => 'required', 'step' => 'any', 'min' => '0']) !!}
                       </div>

                       <!-- End Price Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('end_price', 'End Price:') !!}
                           {!! Form::number('end_price', null, ['class' => 'form-control', 'step' => 'any', 'min' => '0']) !!}
                       </div>

                       <!-- Featured Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('is_featured', 'Featured:') !!}
                           {!! Form::checkbox('is_featured', 1)!!}
                       </div>

                       <!-- National ID Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('national_id', 'Allow National ID:') !!}
                           {!! Form::checkbox('national_id', 1)!!}
                       </div>

                       <!-- Weekly Suggestions Field -->
                       <div class="form-group col-sm-6">
                           {!! Form::label('week_suggest', 'Weekly Suggestions:') !!}
                           {!! Form::checkbox('week_suggest', 1)!!}
                       </div>

                       <div class="form-group col-sm-12">
                           <img src="{{asset($event->media->image)}}" height="50" width="50">
                           {!! Form::label('image', 'Default Image:') !!}
                           {!! Form::file('image') !!}
                       </div>

                       <div class="images">
                           <div class="form-group col-sm-12">
                               {!! Form::label('image', 'Gallery:') !!}<br>
                           </div>
                           @foreach($event->gallery as $key=>$media)
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

                       <div class="form-group col-sm-6">
                           {!! Form::label('category', 'Categories:') !!}<br>
                           <select name="categories[]" class="form-control select2" id="select2" multiple>
                               @foreach($categories as $category)
                                   @if($event->categories->contains((isset($category) ? $category->id : 0)))
                                       <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                   @else
                                       <option value="{{$category->id}}">{{$category->name}}</option>
                                   @endif
                               @endforeach
                           </select>
                       </div>

                       <div class="form-group col-sm-6">
                           {!! Form::label('sub_category', 'Sub Categories:') !!}<br>
                           <select name="sub_categories[]" class="form-control select2" id="select2" multiple>
                               @foreach($sub_categories as $sub_category)
                                   @if($event->sub_categories->contains((isset($sub_category) ? $sub_category->id : 0)))
                                       <option value="{{$sub_category->id}}" selected>{{$sub_category->name}}</option>
                                   @else
                                       <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                   @endif
                               @endforeach
                           </select>
                       </div>


                       <div class="form-group col-sm-12">
                           <strong><h3>Event Tags</h3></strong>
                       </div>

                       <div class="tags">
                           @if (count ($event->tags)>0)

                               @foreach($event->tags as $key=> $tag)
                                   <div class="form-group col-sm-4 tags-{{$key}}">
                                       {!! Form::label('name', 'Name:') !!}<br>
                                       <input type="text" name="tags[{{$key}}][name]" class='form-control' value="{{$tag->name}}">
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
                           @else

                                   <div class="form-group col-sm-4">
                                       {!! Form::label('name', 'Name:') !!}<br>
                                       <input type="text" name="tags[0][name]" class='form-control'>
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
                           @endif
                       </div>

                       <div class="form-group col-sm-12">
                            <strong><h3>Tickets</h3></strong>
                       </div>

                       <div class="tickets">
                       @if (count ($event->event_tickets)>0)

                           @foreach($event->event_tickets as $key=> $ticket)
                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('type', 'Type:') !!}<br>
                                   <input type="text" name="event_tickets[{{$key}}][type]" required class='form-control' value="{{$ticket->type}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('description', 'Description:') !!}<br>
                                   <input type="text" name="event_tickets[{{$key}}][description]" required class='form-control' value="{{$ticket->description}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   {!! Form::label('price', 'Price:') !!}<br>
                                   <input type="number" name="event_tickets[{{$key}}][price]" min="0" step='any' class='form-control' value="{{$ticket->price}}">
                               </div>

                               <div class="form-group col-sm-3 {{$key}}">
                                   <input type="hidden" name="event_tickets[{{$key}}][id]" value="{{$ticket->id}}">
                                   {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
                                   <input type="number" name="event_tickets[{{$key}}][number_of_tickets]" min="0" class='form-control' value="{{$ticket->number_of_tickets}}">
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

                           @else
                                <div class="form-group col-sm-3">
                                    {!! Form::label('type', 'Type:') !!}<br>
                                    <input type="text" name="event_tickets[0][type]" required class="form-control">
                                    <!-- {!! Form::text('event_tickets[0][type]') !!} -->
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('description', 'Description:') !!}<br>
                                    <input type="text" name="event_tickets[0][description]" required class="form-control">
                                    <!-- {!! Form::text('event_tickets[0][description]') !!} -->
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('price', 'Price:') !!}<br>
                                    <input type="number" name="event_tickets[0][price]" min="0" class="form-control" step="any">
                                    <!-- {!! Form::text( 'event_tickets[0][price]') !!} -->
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('number_of_tickets', 'Number Of Tickets:') !!}<br>
                                    <input type="number" name="event_tickets[0][number_of_tickets]" min="0" class="form-control">
                                    <!-- {!! Form::number( 'event_tickets[0][number_of_tickets]') !!} -->
                                </div>
                                <!-- Telephone Field -->
                                <div class="form-group col-sm-12">
                                    <br>
                                    <a style="height: 40px;"  href="#" id="add_more_tickets" class='btn btn-default'><strong>Add More </strong></a>
                                </div>
                            @endif
                       </div>

                       <div class="form-group col-sm-12">
                           <strong><h3>Ticket Dates</h3></strong>
                       </div>

                       <div class="ticket_dates">
                       @if (count ($event->ticket_dates)>0)
                           @foreach($event->ticket_dates as $key=> $ticket_date)
                               <div class="form-group col-sm-4 ticket_dates-{{$key}}">
                                   <input type="hidden" name="ticket_dates[ticket_dates-{{$key}}][id]" value="{{$ticket_date->id}}">
                                   {!! Form::label('date', 'Date:') !!}<br>
                                   <input type="date" name="ticket_dates[ticket_dates-{{$key}}][date]" required class='form-control' value="{{$ticket_date->date}}">
                               </div>

                               <div class="form-group col-sm-4 ticket_dates-{{$key}}">
                                   {!! Form::label('time', 'Time:') !!}<br>
                                   <input type="time" name="ticket_dates[ticket_dates-{{$key}}][time]" required class='form-control' value="{{$ticket_date->time}}">
                               </div>

                               <!-- Telephone Field -->
                               @if($key==0)
                                   <div class="form-group col-sm-12">
                                       <br>
                                       <a style="height: 40px;"  href="#" id="add_more_ticket_dates" class='btn btn-default'><strong>Add More </strong></a>
                                   </div>
                               @else
                                   <div class='form-group col-sm-12 ticket_dates-{{$key}}'>
                                       <a href='#' class='btn btn-default minus' id='ticket_dates-{{$key}}'>
                                           <i class='fa fa-minus'></i>
                                       </a>
                                   </div>
                               @endif

                           @endforeach

                           @else
                                <div class="form-group col-sm-4">
                                    {!! Form::label('date', 'Date:') !!}<br>
                                    <input type="date" name="ticket_dates[0][date]" required class="form-control">
                                    <!-- {!! Form::date('ticket_dates[0][date]') !!} -->
                                </div>
                                <div class="form-group col-sm-4">
                                    {!! Form::label('time', 'Time:') !!}<br>
                                    <input type="time" name="ticket_dates[0][time]" required class="form-control">
                                    <!-- {!! Form::time('ticket_dates[0][time]') !!} -->
                                </div>
                                <!-- Telephone Field -->
                                <div class="form-group col-sm-4">
                                    <br>
                                    <a style="height: 40px;"  href="#" id="add_more_ticket_dates" class='btn btn-default'><strong>Add More </strong></a>
                                </div>
                           @endif
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
                           {!! Form::number('max_of_cash_tickets', null, ['class' => 'form-control']) !!}
                       </div>

                       <!-- Max Number of Pay Later Tickets Field -->
                       <div class="form-group col-sm-4">
                           {!! Form::label('max_of_pay_later_tickets', 'Max Number of Pay Later Tickets:') !!}
                           {!! Form::number('max_of_pay_later_tickets', null, ['class' => 'form-control']) !!}
                       </div>

                        <!-- Max Number of Free Tickets Field -->
                        <div class="form-group col-sm-4">
                            {!! Form::label('max_of_free_tickets', 'Max Number of Free Tickets:') !!}
                            {!! Form::number('max_of_free_tickets', null, ['class' => 'form-control']) !!}
                        </div>

                       <div class="form-group col-sm-12">
                           <strong><h3>Event Social Media</h3></strong>
                       </div>

                       <div class="social_media">
                           @foreach($social_media as $key=> $social)
                               <div class="form-group col-sm-6">
                                   {!! Form::label('social_media', ''.$social['name']. ' : ') !!}<br>
                                   @if($event->social_media->contains((isset($social) ? $social['id'] : 0)))
                                       {!! Form::label('social_url', 'URL') !!}<br>
                                       {!! Form::url('social_media_inputs['.$social['id']. '][url]',$event->social_media()->find($social['id'])->pivot->url,['class'=>'form-control']) !!}
                                       {!! Form::label('social_name', 'Name') !!}<br>
                                       {!! Form::text('social_media_inputs['.$social['id']. '][name]',$event->social_media()->find($social['id'])->pivot->name,['class'=>'form-control']) !!}

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
                           <strong><h3>Event Notification</h3></strong>
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
                           <a href="{!! route('events.index') !!}" class="btn btn-default">Cancel</a>
                       </div>

                   {!! Form::close() !!}

                   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                        <script>
                                $('.datepicker-container').data('datepicker') {}
                            </script>
                           <script>
                               $("#add_more").click(function (e) {
                                   e.preventDefault();
                                   var id=uniqId();
                                   $('.images').append("<div class='form-group col-sm-6 "+id+"'><input type='file' name='images[]'></div> <div class='form-group col-sm-6 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div>");
                               })
                               $(".images").on('click','.minus',(function (e) {
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
                               $('.tags').append("<div class='form-group col-sm-4 "+id+"'><input type='text' class='form-control' name='tags["+id+"][name]'></div> " +
                                   "<div class='form-group col-sm-4 "+id+"'><input type='file' name='tags["+id+"][image]'></div>" +
                                   "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
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


                       <script>
                           $("#add_more_tickets").click(function (e) {
                               e.preventDefault();
                               var id=uniqId();
                               $('.tickets').append("<div class='form-group col-sm-3 "+id+"'><input type='text' class='form-control' name='event_tickets["+id+"][type]' required></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input type='text' class='form-control' name='event_tickets["+id+"][description]' required></div>" +
                                   "<div class='form-group col-sm-3 "+id+"'><input type='number' step='any' class='form-control' name='event_tickets["+id+"][price]'></div> " +
                                   "<div class='form-group col-sm-3 "+id+"'><input type='number' class='form-control' name='event_tickets["+id+"][number_of_tickets]'></div>" +
                                   "<div class='form-group col-sm-12 "+id+"'><a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
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
                           $("#add_more_ticket_dates").click(function (e) {
                               e.preventDefault();
                               var id=uniqId();
                               $('.ticket_dates').append("<div class='form-group col-sm-4 "+id+"'><input type='date' required class='form-control' name='ticket_dates["+id+"][date]'></div> " +
                                   "<div class='form-group col-sm-4 "+id+"'><input type='time' class='form-control' required name='ticket_dates["+id+"][time]'></div>" +
                                   "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
                           })
                           $(".ticket_dates").on('click','.minus',(function (e) {
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
