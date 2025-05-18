<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read roles')->only('index');
        $this->middleware('permission:write roles')->only('store','create');
        $this->middleware('permission:edit roles')->only(['show', 'update']);
        $this->middleware('permission:delete roles')->only('destroy');
    }
    public function index()
    {
        $role = Role::orderByDesc('id')->get();
        return Inertia::render('Roles/Index', [
            'data' => $role
        ]);
    }
    public function create()
    {
        $permission = Permission::get();

        return Inertia::render('Roles/CreateAndUpdate', [
            'permissions' => $permission
        ]);
    }
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id); // Fetch role and its permissions
        $permissions = Permission::get(); // Fetch all permissions

        return Inertia::render('Roles/CreateAndUpdate', [
            'role' => $role, // Send the role data
            'permissions' => $permissions, // Send the permissions data
        ]);
    }

    public function store(RoleRequest $request)
    {
        $validatedData = $request->validated();

        // Step 1: Create the role using the validated name and guard_name (usually 'web' or 'api')
        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'web'  // You can change 'web' to 'api' if you're using API guard
        ]);

        // Step 2: Assign permissions to the role (assuming permissions are passed as an array of IDs)
        $permissions = $validatedData['permission'];  // Expecting permission array from RoleRequest
        $role->syncPermissions($permissions);

        // Step 3: Return success response
        return redirect()->back()->with([
            'message' => 'ڕۆڵەکە بەسەرکەوتووی زیادکرا',
        ]);
    }
    public function update(Request $request, $id)
    {
        // Step 1: Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string',
            'permission' => 'nullable|array', // Allow null or an empty array for permissions
        ]);

        // Step 2: Find the role by ID
        $role = Role::findOrFail($id);

        // Step 3: Update the role's name and guard_name (if necessary)
        $role->update([
            'name' => $validatedData['name'],
            'guard_name' => 'web',  // You can change 'web' to 'api' if you're using API guard
        ]);

        // Step 4: Update or remove permissions
        $permissions = $validatedData['permission'] ?? []; // Default to an empty array if 'permission' is null
        $role->syncPermissions($permissions); // syncPermissions will remove all if the array is empty
        return redirect()->back()->with(
            [
                'message' => 'ڕۆڵەکە بەسەرکەوتووی نوێکرایەوە',
            ],
        );
    }
    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return redirect()->back()->with([
                'message' => 'ڕۆڵـــەکە بەسەرکەوتووی سڕایــەوە',
            ]);
        } else {
            return redirect()->back()->with([
                'message' => 'نەدۆزرایــەوە کێـــشەیەک هەیە!',
            ]);
        }
    }
}
