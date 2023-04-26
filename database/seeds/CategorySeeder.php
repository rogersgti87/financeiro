<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(DB::table('categories')->count() < 1){

            DB::table('categories')->insert([
                'name'          =>  'Luz',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Agua',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Internet',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Moradia',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Lazer',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Alimentação',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

            DB::table('categories')->insert([
                'name'          =>  'Transporte',
                'status'        =>  'Ativo',
                'created_at'    =>  \Carbon\Carbon::now(),
                'updated_at'    =>  \Carbon\Carbon::now(),
            ]);

        }


    }
}
