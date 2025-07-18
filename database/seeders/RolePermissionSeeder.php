<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'manage users',
            'view reports',
            'create letter',
            'edit letter',
            'delete letter',
            'approve letter',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $tupusat = Role::firstOrCreate(['name' => 'tupusat']);
        $tuunit = Role::firstOrCreate(['name' => 'tuunit']);

        // Assign permissions to roles
        $admin->syncPermissions(Permission::all());

        $tupusat->syncPermissions([
            'create letter',
            'edit letter',
            'delete letter',
            'approve letter',
        ]);

        $tuunit->syncPermissions([
            'create letter',
            'edit letter',
        ]);
    }
}