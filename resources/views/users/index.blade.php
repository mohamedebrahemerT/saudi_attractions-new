

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1><hr>
    </section>
    <div class="clearfix">
        <div class="form-group col-sm-2">
            {!! Form::label('status', 'Status:') !!}
            <select name="is_blocked" class="form-control">
                <option value="">Select Option</option>
                <option value="0">Un Blocked</option>
                <option value="1">Blocked</option>
            </select>
        </div>
        <form action="" class="form-group">
                {!! Form::submit('Export', ['class' => 'btn btn-primary pull-left','name'=>'export', 'style' => "margin-top: 25px;"]) !!}
        </form>
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
                        <th>Gender</th>
                        <th>Data Of Birth</th>
                        <th>Jeddah Attraction ID</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{!! $user->name !!}</td>
                            <td>{!! $user->email !!}</td>
                            <td>{!! $user->mobile_number !!}</td>
                            <td>{!! $user->gender !!}</td>
                            <td>{!! $user->birth_date !!}</td>
                            <td>{!! $user->ja_id !!}</td>
                            <td>
                                <div class='btn-group'>
                                    @if($user->is_blocked==0)
                                        <a href="{!! route('users.block', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ban-circle"></i></a>
                                    @else
                                        <a href="{!! route('users.unblock', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-check"></i></a>
                                    @endif
                                </div>
                            </td>
                            <td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection



