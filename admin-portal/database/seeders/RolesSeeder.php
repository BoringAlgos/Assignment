<?php

namespace Database\Seeders;

// database/seeders/RolesSeeder.php

// database/seeders/RolesSeeder.php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Define roles
        $roles = ['Claim Manager', 'Adjustor', 'Surveyor', 'Workshop', 'Admin'];

        // Create roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Attach permissions to Admin role
        $admin = Role::where('name', 'Admin')->first();
        $permissions = ['users-view','users-add','users-edit','workflow-view','workflow-add','workflow-delete','claim-view','assign-adjustor', 'assign-surveyor', 'claim-approve', 'claim-update', 'claim-review','job-start','job-update','job-complete'];

        $admin->givePermissionTo($permissions);
    }
}

