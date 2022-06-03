@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Locale
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($locale, ['route' => ['locales.update', $locale->id], 'method' => 'patch']) !!}

                        @include('locales.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection