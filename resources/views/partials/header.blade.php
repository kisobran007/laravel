<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{route('products.index')}}">Brand</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{route('products.index')}}"><i class="fas fa-home"></i> Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('product.shoppingCart')}}">
            <i class="fas fa-shopping-cart"></i> Shopping Cart
            <span class="badge badge-secondary">{{Session::has('cart') ? Session::get('cart')->totalQuantity : ''}}</span>
        </a>

        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user"></i> User management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              @if (Auth::check())
                <a class="dropdown-item" href="{{route('getprofile')}}">Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('getlogout')}}">Log out</a>
              @else
                <a class="dropdown-item" href="{{route('getsignup')}}">Signup</a>
                <a class="dropdown-item" href="{{route('getsignin')}}">Signin</a>
              @endif


          </div>
        </li>
      </ul>
    </div>
  </nav>
