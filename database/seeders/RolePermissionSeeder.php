<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // ADMIN
        $admin_only_perms = [
            'manage users',          // Create, edit, delete users and assign roles
            'delete projects',       // Permanent removal of a project
            'delete tickets',        // Permanent removal of a ticket
            'manage status/priority',
            'create projects',
             'edit projects',
             'view roles', // Create/edit system-wide Statuses and Priorities
        ];

        // General/Worker permissions - Shared between Admin and User
        $general_perms = [
            // Project View Permissions
            'view projects',

            // Ticket Management Permissions
            'create tickets',
            'view all tickets',      // Allows user to see all active tickets
            'edit tickets',          // Allows user to update ticket details (status, assignment, comments)
            'assign tickets',
            'create projects',
             'edit projects',     // Allows user to assign/reassign tickets

            // General Permissions
            'view kanban',
        ];

        // Project Creation/Editing (Often restricted but not as sensitive as deletion)
        $project_manager_perms = [
             'create projects',
             'edit projects',
        ];


        // Combine all permissions for creation
        $all_permissions = array_merge($admin_only_perms, $general_perms, $project_manager_perms);

        // Create the permissions in the database
        foreach ($all_permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }


        // 2. Define the two primary roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);


        // 3. Attach permissions to the 'admin' role
        // Admins get ALL permissions (Full Control).
        $admin->syncPermissions(Permission::all());


        // 4. Attach permissions to the 'user' role
        // Users get all the general permissions needed to perform daily tasks.
        $user->syncPermissions($general_perms);
    }
}
