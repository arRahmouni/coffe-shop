<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $with = ['categories'];

    protected $appends = [
        'created_at_format',
        'image_path',
        'price_with_currency',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    public function getDataForApi($id, $isCollection = false) : mixed
    {
        $modelCollection = $this->whereHas('categories', fn($query) => $query->whereBelongsTo(request()->user()));

        if($isCollection) {
            return $modelCollection->latest();
        }

        return $modelCollection->findOrFail($id);
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    protected function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($attributes['image']) ? asset('storage/' . $attributes['image']) : null,
        );
    }

    protected function priceWithCurrency(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "$" . number_format($attributes['price'], 2),
        );
    }
}
