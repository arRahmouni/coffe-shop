<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = \App\Models\Category::class;

    public function definition(): array
    {
        return [
            'user_id'   => fake()->randomElement($this->getUserIds()),
            'is_active' => fake()->boolean(90),
            'name'      => $this->fakeName(),
            'slug'      => fn (array $attributes) => Str::slug($attributes['name']),
            'image'     => $this->getImagePath(),
        ];
    }

    private function getImagePath(): string
    {
        $imageFiles = glob(public_path('images/categories/*.{jpg,jpeg,png,gif,webp}'), GLOB_BRACE);

        if (empty($imageFiles)) {
            throw new \Exception('No category images found in public/images/categories');
        }

        $randomImagePath = fake()->randomElement($imageFiles);

        $image = new UploadedFile(
            $randomImagePath,
            basename($randomImagePath),
            mime_content_type($randomImagePath),
            null,
            true 
        );

        return $image->store('images/categories', 'public');
    }

    private function fakeName(): string
    {
        return fake()->randomElement([
            'Espresso Blends',
            'Single Origin Coffee',
            'Cold Brew Coffee',
            'Dark Roast Coffee',
            'Medium Roast Coffee',
            'Light Roast Coffee',
            'Decaf Coffee',
            'Flavored Coffee',
            'Organic Coffee',
            'Coffee Pods',
            'Coffee Grounds',
            'Whole Bean Coffee',
            'French Press Coffee',
            'Pour Over Coffee',
            'Turkish Coffee',
            'Vietnamese Coffee',
            'Italian Roast Coffee',
            'Colombian Coffee',
            'Ethiopian Coffee',
            'Brazilian Coffee',
            'Sumatra Coffee',
            'Costa Rican Coffee',
            'Hawaiian Kona Coffee',
            'Jamaican Blue Mountain Coffee',
            'Iced Coffee',
            'Nitro Cold Brew',
            'Latte',
            'Cappuccino',
            'Mocha',
            'Macchiato',
            'Flat White',
            'Americano',
            'Cortado',
            'Affogato',
            'Ristretto',
            'Lungo',            
            'Coffee Blends',
            'Seasonal Coffee',
            'Fair Trade Coffee',
            'Shade-Grown Coffee',
            'Specialty Coffee',
            'Artisan Coffee',
            'Micro-Lot Coffee',
            'Coffee Sampler Packs',
            'Coffee Gift Sets',
            'Coffee Subscriptions',
            'Coffee Syrups',
            'Coffee Creamers',
            'Coffee Accessories',
            'Coffee Brewing Kits',
        ]);
    }

    private function getUserIds()
    {
        $userIds = User::all()->pluck('id')->toArray();
        $newUserIds = [];

        if (empty($userIds) || count($userIds) < 20) {
            $newUserIds = User::factory(20)->verified()->create()->pluck('id')->toArray();
        }

        return array_merge($userIds, $newUserIds);
    }
}