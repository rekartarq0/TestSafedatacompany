<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::firstOrCreate(['name' => 'admin']);
        $role_user = Role::firstOrCreate(['name' => 'user']);

        $permission_read = Permission::firstOrCreate(['name' => 'read articles']);
        $permission_write = Permission::firstOrCreate(['name' => 'write articles']);
        $permission_edit = Permission::firstOrCreate(['name' => 'edit articles']);
        $permission_delete = Permission::firstOrCreate(['name' => 'delete articles']);

        $permission_user_read = Permission::firstOrCreate(['name' => 'read users']);
        $permission_user_write = Permission::firstOrCreate(['name' => 'write users']);
        $permission_user_edit = Permission::firstOrCreate(['name' => 'edit users']);
        $permission_user_delete = Permission::firstOrCreate(['name' => 'delete users']);

        $permission_roles_read = Permission::firstOrCreate(['name' => 'read roles']);
        $permission_roles_write = Permission::firstOrCreate(['name' => 'write roles']);
        $permission_roles_edit = Permission::firstOrCreate(['name' => 'edit roles']);
        $permission_roles_delete = Permission::firstOrCreate(['name' => 'delete roles']);

        //authores
        $permission_authors_read = Permission::firstOrCreate(['name' => 'read authors']);
        $permission_authors_write = Permission::firstOrCreate(['name' => 'write authors']);
        $permission_authors_edit = Permission::firstOrCreate(['name' => 'edit authors']);
        $permission_authors_delete = Permission::firstOrCreate(['name' => 'delete authors']);



        // permision of the users
        $permissions_admin =
            [
                $permission_delete,
                $permission_edit,
                $permission_read,
                $permission_write,
                //users
                $permission_user_read,
                $permission_user_write,
                $permission_user_edit,
                $permission_user_delete,
                //roles
                $permission_roles_read,
                $permission_roles_write,
                $permission_roles_edit,
                $permission_roles_delete

            ];

        $permission_user = [$permission_read, $permission_write];
        //assign permissions to the admin,user
        // $role_admin->syncPermissions($permissions_admin);
        // $role_user->syncPermissions($permission_user);
    }
}
