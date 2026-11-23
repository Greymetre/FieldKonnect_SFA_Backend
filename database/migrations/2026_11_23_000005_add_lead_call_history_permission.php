<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Separate "lead_call_history_access" permission for Lead Management >
 * Lead Call History (it used call_management_access before).
 *
 *  - Granted to: superadmin, Inside Sales, Management.
 *  - call_management_access is removed from Inside Sales and Management, so
 *    they keep Lead Call History but no longer see Call Management.
 *
 * Role names are matched case-insensitively with "_" / "-" treated as spaces
 * (e.g. "Inside Sales", "inside_sales", "Inside Sale"). The matched roles are
 * printed when the migration runs.
 */
return new class extends Migration
{
    private const GUARD = 'users';
    private const PERMISSION = 'lead_call_history_access';
    private const GRANT_TO = ['superadmin', 'inside sales', 'inside sale', 'management'];
    private const REMOVE_CALL_MANAGEMENT_FROM = ['inside sales', 'inside sale', 'management'];

    public function up(): void
    {
        $permissionId = DB::table('permissions')->where('name', self::PERMISSION)->where('guard_name', self::GUARD)->value('id')
            ?: DB::table('permissions')->insertGetId([
                'name' => self::PERMISSION,
                'guard_name' => self::GUARD,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $grantRoles = $this->roles(self::GRANT_TO);
        foreach ($grantRoles as $roleId => $name) {
            DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }
        echo '  '.self::PERMISSION.' given to: '.($grantRoles->isEmpty() ? 'NO ROLE MATCHED' : $grantRoles->implode(', '))."\n";

        $callManagementId = DB::table('permissions')->where('name', 'call_management_access')->where('guard_name', self::GUARD)->value('id');
        $removeRoles = $this->roles(self::REMOVE_CALL_MANAGEMENT_FROM);
        if ($callManagementId && $removeRoles->isNotEmpty()) {
            DB::table('role_has_permissions')
                ->where('permission_id', $callManagementId)
                ->whereIn('role_id', $removeRoles->keys())
                ->delete();
        }
        echo '  call_management_access removed from: '.($removeRoles->isEmpty() ? 'NO ROLE MATCHED' : $removeRoles->implode(', '))."\n";

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('name', self::PERMISSION)->where('guard_name', self::GUARD)->value('id');
        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        // Give call_management_access back to the roles it was taken from.
        $callManagementId = DB::table('permissions')->where('name', 'call_management_access')->where('guard_name', self::GUARD)->value('id');
        if ($callManagementId) {
            foreach ($this->roles(self::REMOVE_CALL_MANAGEMENT_FROM)->keys() as $roleId) {
                DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $callManagementId, 'role_id' => $roleId]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** Roles (id => name) of the users guard whose normalized name is in the list. */
    private function roles(array $names)
    {
        return DB::table('roles')->where('guard_name', self::GUARD)->get(['id', 'name'])
            ->filter(fn ($role) => in_array(strtolower(trim(preg_replace('/[\s_\-]+/', ' ', $role->name))), $names, true))
            ->pluck('name', 'id');
    }
};
