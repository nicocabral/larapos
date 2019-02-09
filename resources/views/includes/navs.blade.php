<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand " href="/ses/portal/views/"  >
    <img src="{{asset('assets/img/brand.png')}}" alt="" class="img-fluid">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor01">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="{{route('index')}}">Quick Book Point of Sale <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item " data-href="products">
        <a class="nav-link" href="{{route('products')}}" ><img src="{{asset('assets/img/products.png')}}" alt="Products" class="img-fluid"> Products</a>
      </li>
      <li class="nav-item " data-href="categories">
        <a class="nav-link" href="{{route('categories')}}"><img src="{{asset('assets/img/cat.png')}}" alt="Category" class="img-fluid"> Category</a>
      </li>
      <li class="nav-item " data-href="sales">
        <a class="nav-link" href="#"><img src="{{asset('assets/img/sales.png')}}" alt="Sales" class="img-fluid"> Sales</a>
      </li>
      <li class="nav-item " data-href="users">
        <a class="nav-link" href="#"><img src="{{asset('assets/img/users.png')}}" alt="Users" class="img-fluid"> Users</a>
      </li>
    </ul>
    
    @if(Auth::check())
    <a href="javascript:void(0);" class="mr-3 myaccount" style="color:#FFFF !important;"><img src="{{asset('assets/img/user.png')}}" alt="User" class="img-fluid">  {!! Auth::user()->first_name. ', '. Auth::user()->last_name!!}</a>
    <a href="javascript:void(0);" class="btnLogout" style="color:#FFFF !important;"><img src="{{asset('assets/img/exit.png')}}" alt="Logout" class="img-fluid"> Logout</a>
    @endif   
  </div>
</nav>