

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Admins</h1>
        <h1 class="pull-right">
            @if(Auth::user()->role->roles_permission->contains($permission['users.create']))
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.create') !!}">Add New</a>
            @endif
        </h1>
    </section>
    <div class="clearfix">
    </div>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-responsive" id="locales-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>User Role</th>
                        @if(Auth::user()->role->roles_permission->contains($permission['users.action']))
                            <th>Actions</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{!! $admin->name !!}</td>
                            <td>{!! $admin->email !!}</td>
                            <td>{!! $admin->mobile_number !!}</td>
                            <td>{!! \Illuminate\Support\Facades\Config::get('user_role.'.$admin['user_role_id']) !!}</td>
                            <td>
                                @if(Auth::user()->role->roles_permission->contains($permission['users.destroy']))
                                    {!! Form::open(['route' => ['users.destroy', $admin->id], 'method' => 'delete']) !!}
                                @endif
                                <div class='btn-group'>
                                    @if(Auth::user()->role->roles_permission->contains($permission['users.edit']))
                                        <a href="{!! route('users.edit', [$admin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                    @endif
                                </div>
                                {!! Form::close() !!}
                            </td>
                            <td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $admins->links() }}
            </div>
        </div>
    </div>
@endsection



