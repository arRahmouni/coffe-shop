@extends('layouts.master', ['title' => 'Categories'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.products', ['username' => request('username'), 'category' => $category->slug]) }}" 
                    class="group block transform transition-transform hover:scale-102 cursor-pointer">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                        <!-- Category Image -->
                        <div class="h-48 relative bg-gray-200 rounded-t-lg overflow-hidden">
                            @if($category->image)
                            <img src="{{ $category->image_path }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-full h-full object-cover transition-opacity group-hover:opacity-90">
                            @else
                            <div class="absolute inset-0 bg-gradient-to-br from-coffee-100 to-coffee-300"></div>
                            @endif
                        </div>
                    
                        <!-- Category Body -->
                        <div class="p-6">
                            <!-- Name and Status -->
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-coffee-800 truncate">
                                    {{ $category->name }}
                                </h3>
                                <span class="px-2 py-1 text-sm rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $category->is_active ? 'Active' : 'Archived' }}
                                </span>
                            </div>
                    
                            <!-- Product Count and CTA -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 text-coffee-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $category->products_count }} products</span>
                                </div>
                                
                                <span class="text-coffee-600 hover:text-coffee-800 transition-colors">
                                    View →
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-500 text-xl mb-4">No categories found</div>
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    </div>
@endsection