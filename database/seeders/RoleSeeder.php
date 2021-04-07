<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory(20)->create();
        foreach(Role::all() as $role){
            $users = User::inRandomOrder()->take(rand(1,3))->pluck('id');
            foreach($users as $user){
                $role->users()->attach($user);
            }
        }
    }
}
