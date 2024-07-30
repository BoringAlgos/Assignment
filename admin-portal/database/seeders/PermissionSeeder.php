<?php

namespace Database\Seeders;

// database/seeders/PermissionSeeder.php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = ['users-view','users-add','users-edit','workflow-view','workflow-add','workflow-delete','claim-view','assign-adjustor', 'assign-surveyor', 'claim-approve', 'claim-update', 'claim-review','job-start','job-update','job-complete'];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
