<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'member']);

    // create user admin
    User::create([
      'name' => 'Syiroth',
      'email' => 'me@syiroth.com',
      'password' => Hash::make('minda123'),
      'email_verified_at' => now(),
    ])->assignRole('admin');

    // create user member
    User::create([
      'name' => 'Miftakhusy Syiroth',
      'email' => 'miftahsyiroth@gmail.com',
      'password' => Hash::make('minda123'),
      'email_verified_at' => now(),
    ])->assignRole('member');
  }
}
