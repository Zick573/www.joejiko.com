<?php
class UserTableSeeder extends Seeder
{
  public function run()
  {
    DB::table('users')->delete();

    User::create(array(
      'email' => 'your@email.com',
      'name' => 'Your Name',
      'password' => Hash::make('first_password')
    ));

    User::create(array(
      'email' => 'another@email.com',
      'name' => 'Another User',
      'password' => Hash::make('second_password')
    ));
  }
}