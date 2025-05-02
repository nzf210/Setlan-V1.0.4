<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_barang' => fake()->regexify('[A-Z0-9]{6}'),
            'nama_barang' => $this->generateMinLengthString(15),
            'merek' => $this->faker->randomElement(['Sidu', 'Aqua', 'Le Mineral', 'Dettol', 'Pilot', 'Apple', 'Samsung', 'Google', 'Yahoo', 'Facebook', 'Xiaomi', 'Lenovo', 'Hp', 'Vaio', 'Random']),
            'category_id' => fake()->numberBetween(1, 17),
            'type' => 'Tipe ' . Str::random(10),
            'harga' => fake()->randomFloat(2, 1000, 100000000),
            'created_by' => fake()->numberBetween(1, 10),
            'updated_by' => null,
            'deleted_by' => null,
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }

    private function generateMinLengthString($minLength)
    {
        $result = '';
        while (strlen($result) < $minLength) {
            $faker = \Faker\Factory::create();
            // Add a space if result is not empty
            if ($result !== '') {
                $result .= ' ';
            }
            // Append a new word to the result
            $result .= $faker->word();
        }
        return $result;
    }
}
