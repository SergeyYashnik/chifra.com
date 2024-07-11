<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();

        $cartItems = Cart::where('id_user', $user_id)->get();

        $productIds = $cartItems->pluck('id_product')->toArray();

        $products = Product::whereIn('id', $productIds)->get();

        $user = Auth::user();

        return view('order.index', compact(['cartItems', 'products', 'user']));
    }
    public function placeOrder(Request $request)
    {
        $user_id = auth()->id();
        $address_id = $request->input('address_id');
        $address = Address::findOrFail($address_id);

        $cartItems = Cart::where('id_user', $user_id)->get();
        $productIds = $cartItems->pluck('id_product')->toArray();
        $products = Product::whereIn('id', $productIds)->get();

        $totalAmount = 0;


        // Создаем запись в таблице orders
        $order = new Order();
        $order->user_id = $user_id;
        $order->email = auth()->user()->email;
        $order->phone = auth()->user()->phone;
        $order->city = $address->city->name;
        $order->street = $address->street;
        $order->house = $address->house;
        $order->apartment = $address->apartment;
        $order->paid = false;  // Заказ еще не оплачен
        $order->total_price = $totalAmount;
        $order->save();

        // Создаем записи в таблице order_products
        foreach ($products as $product) {
            $cartItem = $cartItems->firstWhere('id_product', $product->id);
            $totalAmount += $product->price * $cartItem->quantity;

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->name_product = $product->name;
            $orderProduct->id_product = $product->id;
            $orderProduct->price = $product->price;
            $orderProduct->quantity = $cartItem->quantity;
            $orderProduct->save();
        }
        $orderId = $order->id;

        return view('order.place', compact(['totalAmount', 'orderId']));
    }
    public function processPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        $cartItems = Cart::where('id_user', auth()->id())->get();
        $productIds = $cartItems->pluck('id_product')->toArray();
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $product->orders += 1;
            $product->save();
        }


        $order->paid = true;
        $order->save();

        //Cart::where('id_user', $order->user_id)->delete();

        return redirect()->route('home')->with('success', 'Заказ успешно оплачен и оформлен!');
    }
    public function show()
    {
        $user_id = auth()->id();
        $orders = Order::where('user_id', $user_id)
            ->where('paid', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('order.show', compact('orders'));
    }
}
