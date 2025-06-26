<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $users = [
            "name"              => "Allan Justine Mascariñas",
            "email"             => "allan@gmail.com",
            "password"          => "password",
            'email_verified_at' => now()
        ];

        $user = User::create($users);

        $roles = [
            [
                "name"              => "admin",
                "guard_name"        => "web",
                "created_at"        => now(),
                "updated_at"        => now(),
            ],
            [
                "name"              => "user",
                "guard_name"        => "web",
                "created_at"        => now(),
                "updated_at"        => now(),
            ]
        ];

        $permissions = [
            [
                "name"              => "admin-access",
                "guard_name"        => "web",
                "created_at"        => now(),
                "updated_at"        => now(),
            ],
            [
                "name"              => 'user-access',
                "guard_name"        => "web",
                "created_at"        => now(),
                "updated_at"        => now(),
            ]
        ];

        Permission::insert($permissions);

        Role::insert($roles);

        $adminPermissions = Permission::where("name", RoleName::ADMIN_ACCESS?->value)
            ->first();

        $userPermissions = Permission::where("name", RoleName::USER_ACCESS?->value)
            ->first();

        $adminRole = Role::where("name", RoleName::ADMIN?->value)
            ->first();

        $userRole = Role::where("name", RoleName::USER?->value)
            ->first();

        $adminRole->syncPermissions([$adminPermissions, $userPermissions]);

        $userRole->syncPermissions([$userPermissions]);

        $user->assignRole($adminRole, $userRole);
    }
}
