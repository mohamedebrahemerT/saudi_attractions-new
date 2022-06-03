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
                    {{ Form::open(array('url' => route('attractions.store_days', $attraction->id), 'files'=>true)) }}

                    <div class="form-group col-sm-12">
                        <strong><h3>Week days options</h3></strong>
                    </div>

                    @foreach($days as $key => $day)
                        <div class="options_{{$day->day}} options">

                            <div class="form-group col-sm-12">
                                <strong><h4> {{ $day->day  }} </h4></strong>
                            </div>

                            <div class="form-group col-sm-12 {{$key}}">
                                {!! Form::label('is_closed', 'Closed:') !!}<br>
                                <input type="hidden" name="attraction_week_days[{{$key}}][venue_day_id]" value="{{$day->id}}">
                                {!! Form::checkbox('attraction_week_days['.$key.'][is_closed]', 1)!!}
                            </div>

                            <div class="form-group col-sm-3 {{$key}}">
                                {!! Form::label('start_time', 'Start Time:') !!}<br>
                                <input type="time" name="attraction_week_days[{{$key}}][start_time]" class='form-control'>
                            </div>

                            <div class="form-group col-sm-3 {{$key}}">
                                {!! Form::label('end_time', 'End Time:') !!}<br>
                                <input type="time" name="attraction_week_days[{{$key}}][end_time]" class='form-control' >
                            </div>

                            <div class="form-group col-sm-3 {{$key}}">
                                {!! Form::label('type', 'Ticket Type:') !!}

                                <select name="attraction_week_days[{{$key}}][types][]" class="form-control select2" id="select_type" multiple>
                                    @foreach($types as $type)

                                        @if($type->attraction_week_days->contains((isset($week_day) ? $week_day->id : 0)))
                                            <option value="{{$type->id}}" selected>{{$type->type}}</option>
                                        @else
                                            <option value="{{$type->id}}">{{$type->type}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-3 {{$key}}">
                                {!! Form::label('addon', 'Ticket Add-ons:') !!}

                                <select name="attraction_week_days[{{$key}}][addons][]" class="form-control select2" id="select_addon"  multiple>
                                    @foreach($addons as $addon)

                                        @if($addon->attraction_week_days->contains((isset($week_day) ? $week_day->id : 0)))
                                            <option value="{{$addon->id}}" selected>{{$addon->name}}</option>
                                        @else
                                            <option value="{{$addon->id}}">{{$addon->name}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>

                            <!-- Telephone Field -->
                            <div class="form-group col-sm-12">
                                <br>
                                <a style="height: 40px;"  href="#" day_id="{{$day->id}}" day_attr="options_{{$day->day}}" class='btn btn-default add_more_options'><strong>Add More </strong></a>
                            </div>
                        </div>
                    @endforeach


                    <div class="form-group col-sm-12">
                        <strong><h3>Exceptional Dates</h3></strong>
                    </div>

                    <div class="exceptional">
                        <div class="form-group col-sm-12 {{$key}}">
                            {!! Form::label('date', 'Date:') !!}<br>
                            <input type="hidden" name="attraction_exceptional_dates[{{$key}}][id]">
                            <input type="date" name="attraction_exceptional_dates[{{$key}}][date]" class='form-control'>
                        </div>

                        <div class="form-group col-sm-3 {{$key}}">
                            {!! Form::label('start_time', 'Start Time:') !!}<br>
                            <input type="time" name="attraction_exceptional_dates[{{$key}}][start_time]" class='form-control'>
                        </div>

                        <div class="form-group col-sm-3 {{$key}}">
                            {!! Form::label('end_time', 'End Time:') !!}<br>
                            <input type="time" name="attraction_exceptional_dates[{{$key}}][end_time]" class='form-control' >
                        </div>

                        <div class="form-group col-sm-3 {{$key}}">
                            {!! Form::label('type', 'Ticket Type:') !!}

                            <select name="attraction_exceptional_dates[{{$key}}][types][]" class="form-control select2" id="select_type" multiple>
                                @foreach($types as $type)

                                    @if($type->attraction_exceptional_dates->contains((isset($exceptional_date) ? $exceptional_date->id : 0)))
                                        <option value="{{$type->id}}" selected>{{$type->type}}</option>
                                    @else
                                        <option value="{{$type->id}}">{{$type->type}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-3 {{$key}}">
                            {!! Form::label('addon', 'Ticket Add-ons:') !!}

                            <select name="attraction_exceptional_dates[{{$key}}][addons][]" class="form-control select2" id="select_addon"  multiple>
                                @foreach($addons as $addon)

                                    @if($addon->attraction_exceptional_dates->contains((isset($exceptional_date) ? $exceptional_date->id : 0)))
                                        <option value="{{$addon->id}}" selected>{{$addon->name}}</option>
                                    @else
                                        <option value="{{$addon->id}}">{{$addon->name}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                            <!-- Telephone Field -->
                            <div class="form-group col-sm-12">
                                <br>
                                <a style="height: 40px;"  href="#" id="add_more_exceptional"  class='btn btn-default add_more_exceptional'><strong>Add More </strong></a>
                            </div>
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('attractions.index') !!}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        $('.datepicker-container').data('datepicker') {}
    </script>

    <script>
        $("#add_more_exceptional").click(function (e) {

            e.preventDefault();
            var id=uniqId();
            $('.exceptional').append(
                "<div class='form-group col-sm-12 "+id+"'><input type='text' class='form-control' name='attraction_exceptional_dates["+id+"][date]'></div> " +
                "<div class='form-group col-sm-3 "+id+"'><input type='time' class='form-control' name='attraction_exceptional_dates["+id+"][start_time]'></div> " +
                "<div class='form-group col-sm-3 "+id+"'><input type='time' class='form-control' name='attraction_exceptional_dates["+id+"][end_time]'></div> " +
                "<div class='form-group col-sm-3 select2-width"+id+"'><select class='form-control select2' multiple name='attraction_exceptional_dates["+id+"][types][]'> "+$('#select_type').html()+"</select></div> " +
                "<div class='form-group col-sm-3 select2-width"+id+"'><select class='form-control select2' multiple name='attraction_exceptional_dates["+id+"][addons][]'>"+$('#select_addon').html()+"</select></div> " +
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
        $(".add_more_options").click(function (e) {
            e.preventDefault();
            var day_attr=$(this).attr('day_attr');
            var day_id=$(this).attr('day_id');
            var id=uniqId();

            $('.'+day_attr).append(
                "<div class='form-group col-sm-12 "+id+"'><input type='hidden' class='form-control' name='attraction_week_days["+id+"][venue_day_id]' value='"+day_id+"'></div> " +
                "<div class='form-group col-sm-3 "+id+"'><input type='time' class='form-control' name='attraction_week_days["+id+"][start_time]'></div> " +
                "<div class='form-group col-sm-3 "+id+"'><input type='time' class='form-control' name='attraction_week_days["+id+"][end_time]'></div> " +
                "<div class='form-group col-sm-3 select2-width"+id+"'><select class='form-control select2' multiple name='attraction_week_days["+id+"][types][]'>"+$('#select_type').html()+"</select></div> " +
                "<div class='form-group col-sm-3 select2-width"+id+"'><select class='form-control select2' multiple name='attraction_week_days["+id+"][addons][]'>"+$('#select_addon').html()+"</select></div> " +
                "<div class='form-group col-sm-12 "+id+"'>  <a href='#' class='btn btn-default minus' day_attr='"+day_attr+"' id='"+id+"'><i class='fa fa-minus'></i></a> </div> ");
        })
        $(".options").on('click','.minus',(function (e) {
            e.preventDefault();
            var id=$(this).attr('id');
            $('.'+id).remove();
        }))
        function uniqId() {
            return Math.round(new Date().getTime() + (Math.random() * 100));
        }
    </script>

@endsection