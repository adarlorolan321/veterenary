<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin' => [
                'store pet',
                'access pet',
                'insert pet',
                'edit pet',
                'update pet',
                'delete pet',
                'view pet',
            ],
            'Admin' => [
                'access profile',

                'access file',
                'insert file',
                'edit file',
                'update file',
                'delete file',
                'view file',
                
                'store pet',
                'access pet',
                'insert pet',
                'edit pet',
                'update pet',
                'delete pet',
                'view pet',

                'download file'
            ],
            'Org Admin' => [
               
            ],
           
          
        ];

        foreach ($roles as $role => $permissions) {
            $db_role = Role::where('name', $role)->first();
            if (!$db_role) {
                // CREATE NEW ROLE
                $db_role = Role::create(['name' => $role]);
            }
            // ADD PERMISSION
            foreach ($permissions as  $permission) {
                $new_permission = Permission::where('name', $permission)->first();
                if (!$new_permission) {
                    $new_permission = Permission::create([
                        'name' => $permission
                    ]);
                }
                if (!$db_role->hasPermissionTo($permission)) {
                    $db_role->givePermissionTo($permission);
                }
            }
        }
        // ASSIGN SUPER ADMIN ROLE
        $admin = User::where('email', 'super@admin.com')->first();
        if (!is_null($admin)) {
            $admin->assignRole('Super Admin'); // ADMIN
        }

        // ASSIGN ADMIN ROLE
        $admin = User::where('email', 'admin@admin.com')->first();
        if (!is_null($admin)) {
            $admin->assignRole('Admin'); // ADMIN
        }

        // ASSIGN ADMIN ROLE
        $admin = User::where('email', 'test@user.com')->first();
        if (!is_null($admin)) {
            $admin->assignRole('Org Admin'); // ADMIN
        }
    }
}
