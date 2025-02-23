<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'is_active',
        'name',
        'slug',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'created_at_format',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDataForApi($id, $isCollection = false) : mixed
    {
        $modelCollection = $this->whereBelongsTo(request()->user());

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
}
