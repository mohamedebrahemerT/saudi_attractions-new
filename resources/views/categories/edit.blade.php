@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Category
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($category, ['url' => route('categories.update',[$category->id]).'?lang='.App::getLocale(), 'method' => 'patch','files'=>true]) !!}

                        @include('categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection