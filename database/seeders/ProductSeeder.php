<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $defaultProducts = [
        [
                'name' => 'Komik Elden Vol 1',
                'category' => 'Others',
                'price' => 500000,
                'stock' => 10,
                'image' => 'komik.jpg',
                'description' => 'Komik Elden Vol 1 kondisi sangat baik, halaman lengkap tanpa sobekan.'
            ],
            [
                'name' => 'Lenovo LOQ',
                'category' => 'Elektronik',
                'price' => 9000000,
                'stock' => 5,
                'image' => 'lenovo_loq.jpg',
                'description' => 'Laptop Lenovo LOQ performa tinggi, cocok untuk gaming maupun kerja.'
            ],
            [
                'name' => 'Mouse Logitech',
                'category' => 'Elektronik',
                'price' => 250000,
                'stock' => 7,
                'image' => 'mouse.jpg',
                'description' => 'Mouse Logitech responsif dan ergonomis, kondisi 95% mulus.'
            ],
            [
                'name' => 'Kursi Vintage',
                'category' => 'Furniture',
                'price' => 1500000,
                'stock' => 7,
                'image' => 'kursi.jpg',
                'description' => 'Kursi vintage kayu jati, nyaman dan kokoh, cocok untuk dekorasi ruangan.'
            ],
        ];

        foreach ($defaultProducts as $p) {
            Product::create($p);
        }

        // dummy products
        Product::factory(10)->create();
    }
}
