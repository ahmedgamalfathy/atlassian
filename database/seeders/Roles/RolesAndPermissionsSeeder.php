<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //all_clients , create_client, edit_client, update_client, delete_client
        // premissions
        $permissions = [
            'all_users',
            'create_user',
            'edit_user',
            'update_user',
            'delete_user',
            'change_user_status',

            'all_roles',
            'create_role',
            'edit_role',
            'update_role',
            'delete_role',

            'all_customers',
            'create_customer',
            'edit_customer',
            'update_customer',
            'delete_customer',
//all_clients , create_client, edit_client, update_client, delete_client
            'all_clients',
            'create_client',
            'edit_client',
            'update_client',
            'delete_client',
//all_service,create_service,edit_service,update_service,delete_service
            'all_service',
            'create_service',
            'edit_service',
            'update_service',
            'delete_service',
//all_scheule,create_scheule,edit_scheule,update_scheule,delete_scheule
            'all_scheule',
            'create_scheule',
            'edit_scheule',
            'update_scheule',
            'delete_scheule',
 //all_reservation,create_reservation,edit_reservation,update_reservation,delete_reservation
            'all_reservation',
            'create_reservation',
            'edit_reservation',
            'update_reservation',
            'delete_reservation',
//all_phone,create_phone,edit_phone,update_phone,delete_phone
            'all_phone',
            'create_phone',
            'edit_phone',
            'update_phone',
            'delete_phone',
//all_email,create_email,edit_email,update_email,delete_email
            'all_email',
            'create_email',
            'edit_email',
            'update_email',
            'delete_email',

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // roles
        $superAdmin = Role::create(['name' => 'superAdmin']);
        $superAdmin->givePermissionTo(Permission::all());


    }
}
