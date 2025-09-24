<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void
    {
        $this->command->info('Synchronizing model permissions.');
        Artisan::call('permissions:sync', ['-C' => true, '-P' => true, '-Y' => true]);
        $this->command->info('Permissions synchronized successfully.');

        /** @var Role */
        $admin = Role::firstOrCreate(['name' => 'admin']);
        /** @var Role */
        $participant = Role::firstOrCreate(['name' => 'participant']);

        $userAdmin = User::firstOrCreate(
        ['name' => 'admin'],
        [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $userAdmin->assignRole('admin');

        $userParticipant = User::firstOrCreate(
        ['name' => 'participant 1'],
            [
                'email' => 'participant1@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $userParticipant->assignRole('participant');

        //add your permission here
        $adminPermissions = Permission::get();
        $admin->syncPermissions($adminPermissions->where('guard_name', 'web'));

        $participantPermissions = Permission::where('name', 'like', 'view Event')
                        ->orWhere('name', 'like', 'view-any Event')
                        ->orWhere('name', 'like', 'view-any Participant')
                        ->orWhere('name', 'like', 'view Participant')
                        ->orWhere('name', 'like', 'edit Participant')
                        ->orWhere('name', 'like', 'view EventTicket')
                        ->orWhere('name', 'like', 'view ActivityTicket')
                        ->orWhere('name', 'like', 'view Activity')->get();
        $participant->syncPermissions($participantPermissions->where('guard_name', 'web'));

        $roleAndPermissionRoles = [
            'view-any Role',
            'view Role',
            'create Role',
            'update Role',
            'delete Role',
            'restore Role',
            'force-delete Role',
            'view-any Permission',
            'view Permission',
            'create Permission',
            'update Permission',
            'delete Permission',
            'restore Permission',
            'force-delete Permission',
        ];

        foreach ($roleAndPermissionRoles as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
            $admin->givePermissionTo(permissions: $permission);
        }
    }
}