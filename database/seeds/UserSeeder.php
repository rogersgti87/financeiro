<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    if(DB::table('users')->count() < 1){

        DB::table('users')->insert([
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
          ]);

    }


  }
}
