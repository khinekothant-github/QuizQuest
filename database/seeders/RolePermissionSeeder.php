<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Hash; // Import Hash for password hashing

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $createUserPermission = Permission::create(['name' => 'create user']);
        $editUserPermission = Permission::create(['name' => 'edit user']);
        $deleteUserPermission = Permission::create(['name' => 'delete user']);
        $viewUserPermission = Permission::create(['name' => 'view user']);
        $viewQuizPermission = Permission::create(['name' => 'view quizzes']);
        $submitQuizPermission = Permission::create(['name' => 'submit quizzes']);
        $viewLeaderBoardPermission = Permission::create(['name' => 'view leaderboard']);
        $viewRolePermission = Permission::create(['name' => 'view roles']);
        $createRolePermission = Permission::create(['name' => 'create role']);
        $editRolePermission = Permission::create(['name' => 'edit role']);
        $deleteRolePermission = Permission::create(['name' => 'delete role']);
        $assignPermissionsPermission = Permission::create(['name' => 'assign permissions']);
        $assignRolePermission = Permission::create(['name' => 'assign role']); // Corrected duplicate

        // Assign permissions to admin role
        $adminRole->permissions()->attach([
            $createUserPermission->id,
            $editUserPermission->id,
            $deleteUserPermission->id,
            $viewUserPermission->id,
            $viewRolePermission->id,
            $createRolePermission->id,
            $editRolePermission->id,
            $deleteRolePermission->id,
            $assignPermissionsPermission->id,
            $assignRolePermission->id,
            $viewQuizPermission->id,
            $submitQuizPermission->id,
            $viewLeaderBoardPermission->id,
        ]);

        // Assign permissions to user role
        $userRole->permissions()->attach([
            $viewQuizPermission->id,
            $submitQuizPermission->id,
            $viewLeaderBoardPermission->id,
        ]);

        // Create default admin account
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Use a secure password
        ]);
        $adminUser->roles()->attach($adminRole->id); // Assign admin role

        // Create default user account
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'), // Use a secure password
        ]);
        $regularUser->roles()->attach($userRole->id); // Assign user role
    }
}
