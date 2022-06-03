@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sub Category
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($subCategory, ['url' => route('subCategories.update',[$subCategory->id]).'?lang='.App::getLocale(), 'method' => 'patch','files'=>true])!!}

                        @include('sub_categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection