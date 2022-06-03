@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contact Us
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">

                        {!! Form::model($contactUs, ['url' => route('contactuses.update',[$contactUs->id]).'?lang='.App::getLocale(), 'method' => 'patch','files'=>true]) !!}
                        
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
                           @if (count ($contactUs->contactMedia)>0)

                               @foreach($contactUs->contactMedia as $key=> $media)
                                   <div class="form-group col-sm-3 contactMedia-{{$key}}">
                                       {!! Form::label('name', 'Name:') !!}<br>
                                       <input type="text" name="contactMedia[{{$key}}][name]" class='form-control' value="{{$media->name}}">
                                   </div>

                                    <div class="form-group col-sm-3 contactMedia-{{$key}}">
                                       {!! Form::label('url', 'URL:') !!}<br>
                                       <input type="text" name="contactMedia[{{$key}}][url]" class='form-control' value="{{$media->url}}">
                                   </div>

                                   <div class="form-group col-sm-3 contactMedia-{{$key}}">
                                       <img src="{{asset($media->media->image)}}" height="30" width="50">
                                       <input type="file" name="contactMedia[{{$key}}][image]" value="{{$media->media->image}}">
                                       <input type="hidden" name="contactMedia[{{$key}}][id]" value="{{$media->id}}">
                                   </div>
                                   <!-- Telephone Field -->
                                   @if($key==0)
                                       <div class="form-group col-sm-3">
                                           <br>
                                           <a style="height: 40px;"  href="#" id="add_more_media" class='btn btn-default'><strong>Add More </strong></a>
                                       </div>
                                   @else
                                       <div class='form-group col-sm-3 contactMedia-{{$key}}'>
                                           <a href='#' class='btn btn-default minus' id='contactMedia-{{$key}}'>
                                               <i class='fa fa-minus'></i>
                                           </a>
                                       </div>
                                   @endif

                               @endforeach
                           @else

                                   <div class="form-group col-sm-3">
                                       {!! Form::label('name', 'Name:') !!}<br>
                                       {!! Form::text('contactMedia[0][name]') !!}
                                   </div>
                                   <div class="form-group col-sm-3">
                                       {!! Form::label('url', 'URL:') !!}<br>
                                       {!! Form::text('contactMedia[0][name]') !!}
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
                           @endif
                       </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('contactuses.index') !!}" class="btn btn-default">Cancel</a>
                        </div>

                   {!! Form::close() !!}

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

               </div>
           </div>
       </div>
   </div>
@endsection