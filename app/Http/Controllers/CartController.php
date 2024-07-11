<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $user_id = auth()->id();

        $cartItems = Cart::where('id_user', $user_id)->get();

        $productIds = $cartItems->pluck('id_product')->toArray();

        $products = Product::whereIn('id', $productIds)->with('oneImage')->get();

        return view('cart.index', compact('cartItems', 'products'));
    }

    public function add(Request $request)
    {
        $cartItem = new Cart();
        $cartItem->id_user = auth()->id();
        $cartItem->id_product = $request->input('product_id');
        $cartItem->quantity = 1;
        $cartItem->save();

        return redirect()->back()->with('success', 'Товар добавлен в корзину!');
    }
    public function updateQuantity(Request $request)
    {
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $user_id = auth()->id();

        $cartItem = Cart::where('id_user', $user_id)->where('id_product', $product_id)->first();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        return redirect()->back()->with('success', 'Количество товара обновлено');
    }
    public function delete(Request $request)
    {
        $product_id = $request->input('product_id');
        $user_id = auth()->id();

        Cart::where('id_user', $user_id)->where('id_product', $product_id)->delete();

        return redirect()->back()->with('success', 'Товар удален из корзины');
    }
}
