<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->createFakeUsers(4);
        $this->createFakeCategories(100);
        $this->createFakeProducts(500);
    }

    private function createFakeUsers(int $count): void
    {
        User::factory($count)->create();
        User::factory($count)->verified()->create();
    }

    private function createFakeCategories(int $count): void
    {
        Category::factory($count)->create();
    }

    private function createFakeProducts(int $count): void
    {
        Product::factory($count)->create();
    }
}
