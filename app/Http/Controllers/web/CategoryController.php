<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends BaseWebController
{
    public function index(Request $request, $username)
    {
        $user = User::verified()->where('username', $username)->with('categories')->firstOrFail();

        $this->data['categories'] = $user->categories()->active()->withCount(['products' => fn($query) => $query->active()])->paginate(config('app.pagination'));

        return view('categories.index', $this->data);
    }

    public function show(Request $request, $username, $category)
    {
        $user = User::verified()->where('username', $username)->with('categories')->firstOrFail();

        $this->data['category'] = $user->categories()->where('slug', $category)->firstOrFail();

        $this->data['products'] = $this->data['category']->products()->active()->paginate(config('app.pagination'));

        return view('products.index', $this->data);
    }

    public function showProduct(Request $request, $username, $category, $product)
    {
        $user = User::verified()->where('username', $username)->with('categories')->firstOrFail();

        $this->data['category'] = $user->categories()->active()->where('slug', $category)->firstOrFail();

        $this->data['product'] = $this->data['category']->products()->active()->where('slug', $product)->firstOrFail();

        return view('products.show', $this->data);
    }
}
