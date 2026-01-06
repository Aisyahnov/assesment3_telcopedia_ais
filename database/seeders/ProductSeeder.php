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
                'name' => 'Kemeja Flanel Premium',
                'category' => 'Fashion',
                'price' => 120000,
                'stock' => 50,
                'image' => 'baju.jpg',
                'description' => 'Kemeja flanel bahan adem, cocok untuk kuliah atau santai.',
            ],
            [
                'name' => 'Celana Chino Slim Fit',
                'category' => 'Fashion',
                'price' => 180000,
                'stock' => 40,
                'image' => 'celana.jpg',
                'description' => 'Celana chino warna cream dengan bahan stretch yang nyaman.',
            ],
            [
                'name' => 'Komik Elden Vol 1',
                'category' => 'Buku',
                'price' => 50000,
                'stock' => 100,
                'image' => 'komik.jpg',
                'description' => 'Petualangan epik di dunia Elden. Bahasa Indonesia.',
            ],
            [
                'name' => 'Kursi Kerja Ergonomis',
                'category' => 'Furniture',
                'price' => 1200000,
                'stock' => 15,
                'image' => 'kursi.jpg',
                'description' => 'Kursi dengan sandaran punggung yang nyaman untuk kerja lama.',
            ],
            [
                'name' => 'Lenovo LOQ Gaming',
                'category' => 'Elektronik',
                'price' => 9000000,
                'stock' => 10,
                'image' => 'lenovo_loq.jpg',
                'description' => 'Laptop gaming gahar dengan RTX 4050 dan i5 Gen 13.',
            ],
            [
                'name' => 'Mouse Logitech RGB',
                'category' => 'Elektronik',
                'price' => 250000,
                'stock' => 30,
                'image' => 'mouse.jpg',
                'description' => 'Mouse gaming wireless dengan lampu RGB yang bisa diatur.',
            ],
        ];

        foreach ($defaultProducts as $p) {
            Product::create($p);
        }
    }
}
