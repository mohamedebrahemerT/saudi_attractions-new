@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Newsletter
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($newsletter, ['route' => ['newsletters.update', $newsletter->id], 'method' => 'patch']) !!}

                        @include('newsletters.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection