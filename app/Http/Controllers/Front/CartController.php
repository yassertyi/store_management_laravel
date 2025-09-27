<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CartController extends Controller
{
    public function index()
    {
        
        return view('frontend.home.sections.cart');
    }
}
