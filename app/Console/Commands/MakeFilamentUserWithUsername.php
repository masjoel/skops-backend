<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class MakeFilamentUserWithUsername extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filament-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Filament user with username field';

    /**
     * Execute the console command.
     */
    // public function handle(): void
    // {
    //     $name = $this->ask('Name');
    //     $username = $this->ask('Username');
    //     $email = $this->ask('Email');
    //     $password = $this->secret('Password');

    //     if (User::where('username', $username)->orWhere('email', $email)->exists()) {
    //         $this->error('User already exists with that username or email!');
    //         return;
    //     }

    //     User::create([
    //         'name' => $name,
    //         'username' => $username,
    //         'email' => $email,
    //         'password' => Hash::make($password),
    //     ]);

    //     $this->info('✅ Filament user created successfully!');
    // }
    public function handle(): void
    {
        $name = $this->ask('Name');
        $username = $this->ask('Username');
        $email = $this->ask('Email');
        $password = $this->secret('Password');
        $role = $this->choice('Role', ['admin', 'operator', 'guru', 'siswa', 'wali kelas', 'wali murid'], 0);

        if (User::where('username', $username)->orWhere('email', $email)->exists()) {
            $this->error('❌ User already exists with that username or email!');
            return;
        }

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // pastikan role ada, jika belum buat
        Role::firstOrCreate(['name' => $role]);
        $user->assignRole($role);

        $this->info("✅ Filament user created successfully with role [$role]!");
    }
}
