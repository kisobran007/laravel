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
use Omnipay\Omnipay;

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
    public function getReduceByOne($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);

        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->route('product.shoppingCart');
    }

    public function getRemoveItem($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }


        return redirect()->route('product.shoppingCart');

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
            $order->payment_type = 'Stripe';
            Auth::user()->orders()->save($order);
        } catch (\Exception $e) {
            return redirect()->route('getcheckout')->with('error', $e->getMessage());
        }
         Session::forget('cart');
        return redirect()->route('products.index')->with('success', 'Successfully purchased products!');
    }

    public function postPaypalCheckout(Request $request){
        if (!Session::has('cart')) {
            return redirect()->route('shop.shoppingCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $paypal = Omnipay::create( 'PayPal_Express' );
        $paypal->setTestMode( true );
        // Using Paypal Sandbox API Credentials
        $paypal->setUsername('merchantaccount123_api1.test.com');
        $paypal->setPassword('DFL4QRQEE2W5ECPU');
        $paypal->setSignature('AC3D3bQpk5l4618mV9bpg.vLjM0YAp8.601TMiA8M.hEKnMa.m2ZdXe4');

        $order_params = [
            'cancelUrl' => route('paypalcancel'),
            'returnUrl' => route('paypalsuccess'),
            "currency" => "usd",
            'description' => 'Test Paypal Charge',
            'amount' => $cart->totalPrice,
        ];

        $response = $paypal->purchase( $order_params )->send();

        if ( $response->isRedirect()){
            return $response->redirect();
        } else {
            return redirect()->route('getcheckout')->with('error', 'We cannot process your payment right now, so please try again later. We are sorry for the inconvenience.');
        }
    }

    public function getPaypalSuccess(){
        if (!Session::has('cart')) {
            return redirect()->route('shop.shoppingCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $order = new Order();
        $order->cart = serialize($cart);
        $order->payment_type = 'Paypal';
        Auth::user()->orders()->save($order);

        Session::forget('cart');
        return redirect()->route('products.index')->with('success', 'Successfully purchased products!');
    }

    public function getPaypalCancel(){
        return redirect()->route('getcheckout')->with('error', 'We cannot process your payment right now, so please try again later. We are sorry for the inconvenience.');
    }
}
