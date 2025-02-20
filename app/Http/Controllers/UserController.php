<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        // dd($users);
        if (request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->role_id == 1 ? 'Admin' : ($row->role_id == 2 ? 'Editor' : 'User');
                })
                ->addColumn('actions', function ($row) {
                    return '
                    <a href="' . route('users.edit', $row->id) . '" class="btn edit-btn" id="edit-' . $row->id . '">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="' . route('users.destroy', $row->id) . '" method="POST" class="inline-block">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn delete-btn" id="delete-' . $row->id . '">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['actions', 'role'])

                ->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $roles = Role::all();
        // dd($roles);
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User Updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
