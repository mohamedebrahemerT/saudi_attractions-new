@extends('layouts.app')

<style>
    .wrapper {
        overflow-y: hidden;
    }
</style>

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Events</h1>
        @if(Auth::user()->role->roles_permission->contains($permission['events.create']))
            <h1 class="pull-right">
               <a class="btn btn-primary pull-right"  href="{!! route('events.create') !!}">Add New</a>
            </h1>
        @endif
        <div class="pull-right" style="width: 30%">
            <form action={{ route('events.search') }} class="form-group">
                <div class="form-group pull-right col-sm-3">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary pull-right', 'style' => 'width:100%;']) !!}
                </div>
                <div class="form-group col-sm-9">
                    {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Title Or Description']) !!}
                </div>
            </form>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('events.table')
            </div>
        </div>
        <div class="text-center">
            {{ $events->links() }}
        </div>
    </div>
@endsection



