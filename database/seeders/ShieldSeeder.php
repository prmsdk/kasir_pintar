<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"panel_user","guard_name":"web","permissions":[]},{"name":"super_admin","guard_name":"web","permissions":["view_reimbursement","view_any_reimbursement","create_reimbursement","update_reimbursement","restore_reimbursement","restore_any_reimbursement","replicate_reimbursement","reorder_reimbursement","delete_reimbursement","delete_any_reimbursement","force_delete_reimbursement","force_delete_any_reimbursement","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"Direktur","guard_name":"web","permissions":["view_reimbursement","view_any_reimbursement","create_reimbursement","update_reimbursement","restore_reimbursement","restore_any_reimbursement","replicate_reimbursement","reorder_reimbursement","delete_reimbursement","delete_any_reimbursement","force_delete_reimbursement","force_delete_any_reimbursement","view_shield::role","view_any_shield::role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"Finance","guard_name":"web","permissions":["view_reimbursement","view_any_reimbursement","create_reimbursement","update_reimbursement","restore_reimbursement","restore_any_reimbursement","replicate_reimbursement","reorder_reimbursement","delete_reimbursement","delete_any_reimbursement","force_delete_reimbursement","force_delete_any_reimbursement","view_shield::role","view_user","view_any_user"]},{"name":"Staff","guard_name":"web","permissions":["create_reimbursement","view_reimbursement","view_any_reimbursement","update_reimbursement","delete_reimbursement","view_user","update_user"]}]';
        $directPermissions = '{"30":{"name":"view_role","guard_name":"web"},"31":{"name":"view_any_role","guard_name":"web"},"32":{"name":"create_role","guard_name":"web"},"33":{"name":"update_role","guard_name":"web"},"34":{"name":"delete_role","guard_name":"web"},"35":{"name":"delete_any_role","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
