<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->delete();

        DB::table('customers')->insert([
            'name' => 'Roger Soares Guimarães',
            'document' => '11976102782',
            'company' => 'RSG SHOP',
            'email' => 'contato@rsgshop.com.br',
            'status' => 'Ativo',
            'cep' => '28920280',
            'address' => 'Rua Gildo Torres de Oliveira',
            'number' => '22',
            'complement' => '',
            'city' => 'Cabo Frio',
            'state' => 'RJ',
            'phone' => '(22) 9.8828-0129',
            'payment_method' => 'pix',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

    }
}
