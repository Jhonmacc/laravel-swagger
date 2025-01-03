<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use Illuminate\Database\Seeder;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 10; $i++) {
            Funcionario::create([
                'nome' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'cargo' => $faker->jobTitle,
                'salario' => $faker->randomFloat(2, 1500, 10000), // Salário entre 1500 e 10000
            ]);
        }
    }
}
