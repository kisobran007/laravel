@extends('layouts.master')
 @section('title')
    Laravel Shopping Cart
@endsection
 @section('content')
 <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="credit-card-tab" data-toggle="tab" href="#creditcard" role="tab" aria-controls="creditcard" aria-selected="true">Credit Card</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="false">Paypal</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="creditcard" role="tabpanel" aria-labelledby="credit-card-tab">
        <div class="row">
                <div class="col-sm-6 col-md-4 offset-4 offset-3">
                    <h1>Pay By Credit Card</h1>
                    <h4>Your Total: ${{ $total }}</h4>
                    <div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'd-none' : ''  }}">
                        {{ Session::get('error') }}
                    </div>
                    <form action="{{ route('postcheckout') }}" method="post" id="checkout-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" class="form-control" required>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="card-name">Card Holder Name</label>
                                    <input type="text" id="card-name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="card-number">Credit Card Number</label>
                                    <input type="text" id="card-number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="card-expiry-month">Expiration Month</label>
                                            <input type="text" id="card-expiry-month" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="card-expiry-year">Expiration Year</label>
                                            <input type="text" id="card-expiry-year" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="card-cvc">CVC</label>
                                    <input type="text" id="card-cvc" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Buy now</button>
                    </form>
                </div>
            </div>
    </div>
    <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
        <div class="container">
            <div class="gateway--info">
                <div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'd-none' : ''  }}">
                    {{ Session::get('error') }}
                </div>
                <h1>Pay By Paypal</h1>
                    <h4>Your Total: ${{ $total }}</h4>
                <div class="gateway--paypal">
                    <form method="POST" action="{{ route('postpaypalcheckout', ['order' => encrypt(mt_rand(1, 20))]) }}">
                        {{ csrf_field() }}

                        <button class="btn btn-success">
                            Pay With Paypal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="{{ URL::to('js/checkout.js') }}"></script>
@endsection
