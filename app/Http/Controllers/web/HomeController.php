<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\BaseWebController;
use App\Models\User;

class HomeController extends BaseWebController
{
    public function index()
    {
        $this->data['coffeeShops'] = User::verified()->withCount('categories')->paginate(config('app.pagination'));

        return view('home', $this->data);
    }
}
