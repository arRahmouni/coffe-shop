<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => fake()->unique()->words(3, true),
            'slug'          => fn (array $attributes) => Str::slug($attributes['name']),
            'description'   => fake()->paragraphs(3, true),
            'image'         => $this->getImagePath(),
            'price'         => fake()->randomFloat(2, 5, 100),
            'is_active'     => fake()->boolean(90),
        ];
    }

    private function getImagePath(): string
    {
        // Get random product image
        $imageFiles = glob(public_path('images/products/*.{jpg,jpeg,png,gif}'), GLOB_BRACE);

        if (empty($imageFiles)) {
            throw new \Exception('No product images found in public/images/products');
        }

        // Create fake uploaded file
        $randomImagePath = fake()->randomElement($imageFiles);

        $image = new UploadedFile(
            $randomImagePath,
            basename($randomImagePath),
            mime_content_type($randomImagePath),
            null,
            true
        );

        return $image->store('images/products', 'public');
    }

    public function configure()
    {
        return $this->afterCreating(function ($product) {
            // Attach 1-3 random categories
            $categories = Category::inRandomOrder()
                ->limit(rand(1, 3))
                ->get();

            if ($categories->isEmpty()) {
                $categories = Category::factory()
                    ->count(3)
                    ->create();
            }

            $product->categories()->attach($categories);
        });
    }
}
