<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Media;
use App\Permission;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;

class UserController extends Controller
{
    public function index()
    {
        if(!isset($_GET['export'])) {
            $users = User::where('user_role_id', null)->latest()->paginate(15);
            return view('users.index')
                ->with('users', $users);
        }
        else
        {
            $users= User::get()->toArray();;
            $first=['ID','Name', 'Username', 'Email', 'Mobile Number', 'Gender', 'Birth Of Date', 'ja_id'];

            $out = fopen('Users.csv', 'w');

            fputcsv($out, $first);
            foreach($users as $user)
            {

                $users=[$user['id'],$user['name'],$user['email'],$user['mobile_number'], $user['username'], $user['gender'], $user['birth_date'], $user['ja_id']];
                fputcsv($out, $user);
            }
            fclose($out);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('Users.csv', 'Users.csv',$headers);
        }
    }

    public function block($id)
    {
        $user = User::find($id)->update(['is_blocked'=> 1]);

        Flash::success('User blocked successfully.');

        return redirect(route('users.index'));
    }

    public function unblock($id)
    {
        $user = User::find($id)->update(['is_blocked'=> 0]);

        Flash::success('User unblocked successfully.');

        return redirect(route('users.index'));
    }

    public function indexAdmin()
    {
        if(!isset($_GET['export'])) {
            $permission = Permission::where('name', 'admins.edit')->pluck('id', 'name')->toArray();
            $permission = Permission::where('name', 'users.create')->orWhere('name', 'admins.edit')->orWhere('name', 'users.edit')->orWhere('name', 'users.action')->orWhere('name', 'users.destroy')->pluck('id', 'name')->toArray();
            if (\Auth::user()->role->roles_permission->contains($permission['admins.edit'])) {
                $admins = User::where('user_role_id', 1)->orWhere('user_role_id', 2)->orWhere('user_role_id', 3)->latest()->paginate(15);
            } else {
                $admins = User::where('user_role_id', 3)->latest()->paginate(15);
            }
            return view('users.admins')
                ->with('admins', $admins)->with('permission', $permission);
        }
        else
        {
            $admins= User::get()->toArray();
            $first=['ID','Name', 'Username', 'Email', 'Mobile Number', 'Gender', 'Birth Of Date', 'ja_id'];

            $out = fopen('Admins.csv', 'w');

            fputcsv($out, $first);
            foreach($admins as $admin)
            {

                $admins=[$admin['id'],$admin['name'],$admin['email'],$admin['mobile_number'], $admin['username'], $admin['gender'], $admin['birth_date'], $admin['ja_id']];
                fputcsv($out, $admin);
            }
            fclose($out);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('Admins.csv', 'Admins.csv',$headers);
        }
    }
    public function create()
    {
        $permission = Permission::where('name', 'super_admins.create')->orWhere('name', 'users_role.create')->orWhere('name', 'admins.edit')->orWhere('name', 'users.create')->orWhere('name', 'admins.create')->pluck('id', 'name')->toArray();
        return view('users.create')->with('permission', $permission);
    }


    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $input['forget_code'] = bin2hex(random_bytes('3'));
        $input['ja_id'] = bin2hex(random_bytes('3'));
        $user = User::create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.admins'));
    }

    public function edit($id)
    {
        $admin = User::find($id);

        if (empty($admin)) {
            Flash::error('User not found');

            return redirect(route('users.admins'));
        }
        $permission = Permission::where('name', 'super_admins.create')->orWhere('name', 'admins.edit')->orWhere('name', 'users_role.create')->orWhere('name', 'users.create')->orWhere('name', 'admins.create')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['admins.edit']) || $admin->user_role_id == 3) {
        return view('users.edit')->with('admin', $admin)->with('permission', $permission);
            }
            return redirect(route('users.admins'));
    }

    public function update($id, UpdateUserRequest $request)
    {
        $admin = User::find($id);

        if (empty($admin)) {
            Flash::error('User not found');

            return redirect(route('users.admins'));
        }
        $input = $request->only('name', 'password', 'mobile_number', 'email', 'user_role_id');
        $admin = $admin->update($input);


        Flash::success('User updated successfully.');

        return redirect(route('users.admins'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.admins'));
        }

        $user->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.admins'));
    }

}
