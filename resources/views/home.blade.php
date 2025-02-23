@extends('layouts.master')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-coffee-900 mb-8 text-center">Discover Local Coffee Shops</h1>

        <!-- Coffee Shops Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($coffeeShops as $shop)
                <a href="{{ route('coffee.index', ['username' => $shop->username]) }}" 
                    class="group block transform transition-transform hover:scale-102 cursor-pointer focus:outline-none focus:ring-2 focus:ring-coffee-500 focus:ring-offset-2"
                    aria-label="View {{ $shop->name }} details">
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 relative bg-gray-200" 
                            style="background-image: url('{{ asset('images/coffe.jpg') }}'); 
                                    background-size: cover; 
                                    background-position: center;">
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-2xl font-bold">{{ $shop->first_name }} {{ $shop->last_name }}</h3>
                                <p class="text-coffee-200">@ {{ $shop->username }}</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-coffee-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-2 text-gray-600">4.5 (128 reviews)</span>
                            </div>

                            <p class="text-gray-600 mb-4">
                                {{ $shop->email }}<br>
                                Specialty coffee since {{ $shop->created_at->format('Y') }}
                            </p>

                            <div class="border-t pt-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>0.5km away</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($coffeeShops->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-500 text-xl mb-4">No coffee shops registered yet</div>
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $coffeeShops->links() }}
        </div>
    </div>
@endsection