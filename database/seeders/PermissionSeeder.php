<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::factory(20)->create();

        foreach(Permission::all() as $permission){
            $roles = Role::inRandomOrder()->take(rand(1,3))->pluck('id');
            foreach($roles as $role){
                $permission->roles()->attach($role);
            }
        }
        foreach(Permission::all() as $permission){
            $users = User::inRandomOrder()->take(rand(1,3))->pluck('id');
            foreach($users as $user){
                $permission->users()->attach($user);
            }
        }
    }
}
