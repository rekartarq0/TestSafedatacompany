<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    // Constructor to apply middleware
    public function __construct()
    {
        $this->middleware('permission:read users')->only('index');
        $this->middleware('permission:write users')->only('store');
        $this->middleware('permission:edit users')->only(['show', 'update']);
        $this->middleware('permission:delete users')->only('destroy');
    }

    // Display the list of users
    public function index(Request $request)
    {
        $query = $request->input('q');
        $roles = Role::select('id', 'name')->get();
        $users = User::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('roles')
            ->get();
        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Create the user
        $user = User::create($data);

        // Assign the role to the user
        $role = Role::find($data['roles']);
        if (!$role) {
            return redirect()->back()->withErrors(['message' => 'The role was not found']);
        }

        $user->assignRole($role);

        return redirect()->back()->with(['message' => 'User created successfully']);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with(['message' => 'User deleted successfully']);
    }
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return redirect()->back()->with([
            'data' => $user
        ]);
    }
    public function update(UserUpdateRequest $request, $id)
    {
        $data = $request->validated();
        // Find the user
        $user = User::findOrFail($id);

        // If a new password is provided, hash it
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // If no new password is provided, retain the old one
            unset($data['password']);
        }

        // Update user data
        $user->update($data);

        // Fetch role by name and assign it
        $role = Role::find($data['roles']);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Sync the user roles
        $user->syncRoles($role);

        return redirect()->back()->with([
            'message' => 'بەسەرکەوتووی بەکارهێنەرەکە نوێکرایەوە',
        ], 200);
    }
}
