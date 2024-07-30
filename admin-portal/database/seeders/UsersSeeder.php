<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Attach roles to users
        $this->attachRoles('Claim Manager', 3, ['claim-view', 'assign-adjustor', 'assign-surveyor', 'claim-approve', 'claim-review', 'claim-update']);
        $this->attachRoles('Adjustor', 3, ['claim-view', 'claim-update', 'claim-approve']);
        $this->attachRoles('Surveyor', 3, ['claim-view', 'claim-review']);
        $this->attachRoles('Workshop', 3, ['claim-view', 'job-start', 'job-update', 'job-complete']);
        $this->attachRoles('Admin', 1, ['users-view', 'users-add', 'users-edit', 'workflow-view', 'workflow-add', 'workflow-delete', 'claim-view', 'assign-adjustor', 'assign-surveyor', 'claim-approve', 'claim-update', 'claim-review', 'job-start', 'job-update', 'job-complete']);

        // Add specific users
        $this->addUserWithRoleAndPermissions('admin@ycountry.com', 'Admin', ['users-view', 'users-add', 'users-edit', 'workflow-view', 'workflow-add', 'workflow-delete', 'claim-view', 'assign-adjustor', 'assign-surveyor', 'claim-approve', 'claim-update', 'claim-review', 'job-start', 'job-update', 'job-complete']);
        
        $this->addUserWithRoleAndAreaCode('adjustor@ycountry.com', 'Adjustor', '0001', ['claim-view', 'claim-update', 'claim-approve']);
        
        $this->addUserWithRoleAndAreaCode('surveyor@ycountry.com', 'Surveyor', '0001', ['claim-view', 'claim-review']);
    }

    protected function attachRoles($roleName, $count, $permissions = [])
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        // Create users and attach the role
        User::factory($count)->create()->each(function ($user) use ($role, $permissions) {
            $user->assignRole($role);

            // Give specific permissions to the user
            $user->givePermissionTo($permissions);

            // Add area_pincode and availability to the user
            $user->update([
                'area_pincode' => $this->getRandomAreaCode(),
                'availability' => true, // All users are initially marked as available
            ]);
        });
    }

    protected function addUserWithRoleAndPermissions($email, $roleName, $permissions = [])
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        // Create user and attach the role
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt('Password'), // Set the password as 'Password'
        ]);

        $user->assignRole($role);

        // Give specific permissions to the user
        $user->givePermissionTo($permissions);

        // Add area_pincode and availability to the user
        $user->update([
            'area_pincode' => $this->getRandomAreaCode(),
            'availability' => true,
        ]);
    }

    protected function addUserWithRoleAndAreaCode($email, $roleName, $areaCode, $permissions = [])
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        // Create user and attach the role
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt('Password'), // Set the password as 'Password'
        ]);

        $user->assignRole($role);

        // Give specific permissions to the user
        $user->givePermissionTo($permissions);

        // Set the provided area_pincode and availability to the user
        $user->update([
            'area_pincode' => $areaCode,
            'availability' => true,
        ]);
    }

    protected function getRandomAreaCode()
    {
        // Dummy US area codes
        $areaCodes = ['10001', '20002', '30003', '40004', '50005'];

        return $areaCodes[array_rand($areaCodes)];
    }
}
