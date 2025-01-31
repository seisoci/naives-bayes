<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();
    Role::create([
      'name' => 'Super Admin',
      'slug' => 'admin',
      'dashboard_url' => 'admin/dashboard',
    ]);

    Admin::create([
      'name' => 'Admin',
      'email' => 'admin@gmail.com',
      'username' => 'admin',
      'active' => 1,
      'password' => bcrypt('admin'),
      'role_id' => 1
    ]);
  }
}
