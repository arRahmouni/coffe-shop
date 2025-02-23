@extends('layouts.master', ['title' => 'Product Details'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('categories.products', ['username' => request('username'), 'category' => request('category')]) }}" 
               class="text-coffee-600 hover:text-coffee-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Products
            </a>
        </div>

        <!-- Product Details Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="h-96 bg-gray-100 relative">
                    @if($product->image)
                        <img src="{{ $product->image_path }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-coffee-100 to-coffee-300"></div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="p-8 space-y-6">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h1 class="text-3xl font-bold text-coffee-900">
                                {{ $product->name }}
                            </h1>
                            <span class="px-3 py-1 text-sm rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $product->is_active ? 'Available' : 'Out of Stock' }}
                            </span>
                        </div>

                        <div class="text-2xl font-bold text-coffee-700">
                            {{ $product->price_with_currency }}
                        </div>
                    </div>

                    @if($product->description)
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @endif

                    <!-- Additional Details -->
                    <div class="space-y-4 text-sm text-gray-600">
                        @if($product->sku)
                            <div class="flex items-center">
                                <span class="w-24 font-medium">SKU:</span>
                                <span>{{ $product->sku }}</span>
                            </div>
                        @endif

                        @if($product->category)
                            <div class="flex items-center">
                                <span class="w-24 font-medium">Category:</span>
                                <span>{{ $product->category->name }}</span>
                            </div>
                        @endif

                        @if($product->weight)
                            <div class="flex items-center">
                                <span class="w-24 font-medium">Weight:</span>
                                <span>{{ $product->weight }} kg</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    @if($product->is_active)
                        <div class="pt-6">
                            <button class="w-full bg-coffee-600 hover:bg-coffee-700 text-white py-3 px-6 rounded-lg 
                                       transition-colors duration-300">
                                Add to Cart
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection