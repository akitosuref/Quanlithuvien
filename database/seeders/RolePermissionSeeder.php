<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view books',
            'create books',
            'edit books',
            'delete books',
            
            'view members',
            'create members',
            'edit members',
            'delete members',
            
            'view lendings',
            'create lendings',
            'edit lendings',
            'delete lendings',
            
            'issue books',
            'return books',
            'reserve books',
            'renew books',
            
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            
            'view events',
            'create events',
            'edit events',
            'delete events',
            
            'view event requests',
            'create event requests',
            'review event requests',
            
            'respond to events',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $librarianRole = Role::create(['name' => 'librarian']);
        $librarianRole->givePermissionTo(Permission::all());

        $memberRole = Role::create(['name' => 'member']);
        $memberRole->givePermissionTo([
            'view books',
            'view members',
            'view lendings',
            'reserve books',
            'renew books',
            'view posts',
            'create posts',
            'edit posts',
            'view events',
            'create event requests',
            'respond to events',
        ]);

        $librarians = User::where('role', 'librarian')->get();
        foreach ($librarians as $librarian) {
            $librarian->assignRole('librarian');
        }

        $members = User::where('role', 'member')->get();
        foreach ($members as $member) {
            $member->assignRole('member');
        }
    }
}
