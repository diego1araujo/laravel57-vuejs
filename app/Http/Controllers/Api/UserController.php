<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            $users = User::latest()->paginate(15);

            return response()->json($users);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|min:6',
        ]);

        $currentPhoto = $user->photo;

        if ($request->photo != $currentPhoto) {
            $imgStrpos = strpos($request->photo, ';');
            $imgSubstr = substr($request->photo, 0, $imgStrpos);
            $name = time() . '.' . explode('/', explode(':', $imgSubstr)[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/') . $name);

            $request->merge([
                'photo' => $name,
            ]);

            $userPhoto = public_path('img/profile/') . $currentPhoto;

            if (file_exists($userPhoto)) {
                @unlink($userPhoto);
            }
        }

        if (!empty($request->password)) {
            $request->merge([
                'password' => Hash::make($request['password']),
            ]);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'success',
        ]);
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
        ]);

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfuly',
        ]);
    }
}
