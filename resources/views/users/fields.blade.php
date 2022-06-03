<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::text('password', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile_number', 'Mobile Number:') !!}
    {!! Form::text('mobile_number', null, ['class' => 'form-control']) !!}
</div>
<!-- User Role Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_role_id', 'User Role:') !!}
    <select name="user_role_id" class="form-control">
        <option value="">Select Option</option>
        @if(Auth::user()->role->roles_permission->contains($permission['super_admins.create']))
        <option value="1">Super Admin</option>
        @endif
        @if(Auth::user()->role->roles_permission->contains($permission['admins.create']))
        <option value="2">Admin</option>
        @endif
        @if(Auth::user()->role->roles_permission->contains($permission['users_role.create']))
        <option value="3">User</option>
        @endif
    </select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.admins') !!}" class="btn btn-default">Cancel</a>
</div>
