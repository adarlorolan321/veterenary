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
                'access dashboard',
                'access profile',

                'access user',
                'insert user',
                'edit user',
                'update user',
                'delete user',
                'view user',

                'access organization',
                'insert organization',
                'edit organization',
                'update organization',
                'delete organization',
                'view organization',

                'access payment',
                'insert payment',
                'edit payment',
                'update payment',
                'delete payment',
                'view payment',

                'access file',
                'insert file',
                'edit file',
                'update file',
                'delete file',
                'view file',

                'access subscription',
                'insert subscription',
                'edit subscription',
                'update subscription',
                'delete subscription',
                'view subscription',


                'access admin',
                'insert admin',
                'edit admin',
                'update admin',
                'delete admin',
                'view admin',

                'access price',
                'insert price',
                'edit price',
                'update price',
                'delete price',
                'view price',

                'download file'
            ],
            'Admin' => [
                'access profile',

                'access file',
                'insert file',
                'edit file',
                'update file',
                'delete file',
                'view file',

                'download file'
            ],
            'Org Admin' => [
                'access dashboard',
                'access org staff',
                'access org invites',
                'access org setting',
                'access profile',
                'access org payment',
                'access org profile',
                'access org subscription',
                'access org payment method',

                'insert org card',
                'edit org card',

                'insert org staff',
                'edit org staff',
                'update org staff',
                'delete org staff',
                'view org staff',

                'access org file',
                'insert org file',
                'edit org file',
                'update org file',
                'delete org file',
                'view org file',
            ],
            'Associate / File Preparer' => [
                'access profile',

                'access org file'
            ],
            'Manager / File Reviewer' => [
                'access profile',

                'access org file'
            ],
            'Partner' => [
                'access dashboard',
                'access org staff',
                'access org invites',
                'access org setting',
                'access profile',
                'access org payment',
                'access org profile',
                'access org subscription',
                'access org payment method',

                'insert org card',
                'edit org card',

                'insert org staff',
                'edit org staff',
                'update org staff',
                'delete org staff',
                'view org staff',

                'access org file',
                'insert org file',
                'edit org file',
                'update org file',
                'delete org file',
                'view org file',
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
