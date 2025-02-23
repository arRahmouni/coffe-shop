@extends('layouts.master', ['title' => $category->name])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('coffee.index', ['username' => request('username')]) }}" 
                class="text-coffee-600 hover:text-coffee-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Categories
            </a>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <a href="{{ route('products.show', ['username' => request('username'), 'category' => $category->slug, 'product' => $product->slug]) }}" 
                    class="group block transform transition-transform hover:scale-102 cursor-pointer">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow     q duration-300">
                        <!-- Product Image -->
                        <div class="h-48 relative bg-gray-200 rounded-t-lg overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image_path }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover transition-opacity group-hover:opacity-90">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-coffee-100 to-coffee-300"></div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-bold text-coffee-800 truncate">
                                    {{ Str::limit($product->name, 30, '...') }}
                                </h3>
                                <span class="px-2 py-1 text-sm rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Available' : 'Out of Stock' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-coffee-600 font-medium">
                                    {{ $product->price_with_currency }}
                                </div>
                            </div>

                            @if($product->description)
                                <p class="mt-3 text-gray-600 text-sm line-clamp-2">
                                    {{ Str::limit($product->description, 50, '...') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($products->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-500 text-xl mb-4">No products found in this category</div>
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
@endsection