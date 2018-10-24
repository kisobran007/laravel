<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use App\Order;
use Session;
use Auth;
use Stripe\Charge;
use Stripe\Stripe;

class ProductController extends Controller
{
    public function getIndex(){
        $products = Product::all();
        return view('shop.index')->with('products', $products);
    }
    public function getAddToCart(Request $request, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->id);

        $request->session()->put('cart', $cart);
        return redirect()->route('products.index');
    }
    public function getCart(){
        if(!Session::has('cart')){
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $passVariables = [
            'products' => $cart->items,
            'totalPrice'=> $cart->totalPrice
        ];
        return view('shop.shopping-cart')->with($passVariables);
    }
    public function getCheckout()
    {
        if (!Session::has('cart')) {
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;
        return view('shop.checkout', ['total' => $total]);
    }
     public function postCheckout(Request $request)
    {
        if (!Session::has('cart')) {
            return redirect()->route('shop.shoppingCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
         Stripe::setApiKey('sk_test_ezuFcc7Op7euIYcfDlFOQiSd');
        try {
            $charge = Charge::create(array(
                "amount" => $cart->totalPrice * 100,
                "currency" => "usd",
                "source" => $request->input('stripeToken'), // obtained with Stripe.js
                "description" => "Test Charge"
            ));
            $order = new Order();
            $order->cart = serialize($cart);
            $order->address = $request->input('address');
            $order->name = $request->input('name');
            $order->payment_id = $charge->id;
            Auth::user()->orders()->save($order);
        } catch (\Exception $e) {
            return redirect()->route('getcheckout')->with('getcheckout', $e->getMessage());
        }
         Session::forget('cart');
        return redirect()->route('products.index')->with('success', 'Successfully purchased products!');
    }
}
