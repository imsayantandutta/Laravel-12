<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch all products from the database
        $products = Product::all();

        // Send to the view
        return view('home', compact('products'));
    }
}
