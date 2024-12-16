<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function store(Product $product, Request $request){
        Cart::add($product->id, $product->name , 1, $product->price);
        return view('layouts._cart')->render();
    }
}
