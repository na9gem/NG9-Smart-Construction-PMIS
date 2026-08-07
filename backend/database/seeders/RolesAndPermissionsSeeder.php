<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [

            'dashboard.view',

            'project.view',
            'project.create',
            'project.update',
            'project.delete',

            'contract.manage',

            'document.manage',

            'progress.manage',

            'inspection.manage',

            'media.manage',

            'user.manage',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $engineer = Role::firstOrCreate([
            'name' => 'engineer',
            'guard_name' => 'web',
        ]);

        $committee = Role::firstOrCreate([
            'name' => 'committee',
            'guard_name' => 'web',
        ]);

        $contractor = Role::firstOrCreate([
            'name' => 'contractor',
            'guard_name' => 'web',
        ]);

        $viewer = Role::firstOrCreate([
            'name' => 'viewer',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions(Permission::all());

        $engineer->givePermissionTo([
            'dashboard.view',
            'project.view',
            'project.create',
            'project.update',
            'contract.manage',
            'document.manage',
            'progress.manage',
            'inspection.manage',
            'media.manage',
        ]);

        $committee->givePermissionTo([
            'dashboard.view',
            'project.view',
            'inspection.manage',
        ]);

        $contractor->givePermissionTo([
            'document.manage',
            'progress.manage',
            'media.manage',
        ]);

        $viewer->givePermissionTo([
            'dashboard.view',
            'project.view',
        ]);
    }
}
