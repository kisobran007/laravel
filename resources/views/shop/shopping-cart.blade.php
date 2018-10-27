@extends('layouts.master')
@section('title')
Laravel Shopping Cart
@endsection
@section('content')
    @if(Session::has('cart'))
        <div class="row">
            <div class="col-sm-6 col-md-6 offset-md-3">
                <ul class="list-group">
                    @foreach($products as $product)
                        <li class="item-group-item">
                            <span class="badge badge-primary">
                                {{$product['quantity']}}
                            </span>
                            <strong>{{ $product['item']['title'] }}</strong>
                            <span class="label label-success">
                                ${{ $product['price'] }}
                            </span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('product.reduceByOne', ['id' => $product['item']['id']]) }}">Reduce by one</a>
                                    </li>
                                    <li>
                                            <a href="{{ route('product.removeItem', ['id' => $product['item']['id']]) }}">Remove All</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-6 offset-3 offset-3">
                <strong>Total: ${{$totalPrice}}</strong>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 col-md-6 offset-3 offset-3">
                <a href="{{ route('postcheckout') }}" type="button" class="btn btn-success">Checkout</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6 col-md-6 offset-3 offset-3">
                <h1>No items in Cart!</h1>
            </div>
        </div>
    @endif
@endsection
