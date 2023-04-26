<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(DB::table('services')->count() < 1){

            DB::table('services')->insert([
                'name' => 'Hospedagem',
                'price' => '30.00',
                'price_trimestral' => '85.50',
                'price_anual' => '324',
                'period' => 'recorrente',
                'status' => 'Ativo',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);

        }


    }
}
