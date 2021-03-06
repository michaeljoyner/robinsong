<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/assets/logotext.png') }}" alt="logo"/>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false"
                    >Products <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/products/index">All Products</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/admin/standard-options/app">Standard Options</a></li>
                    </ul>
                </li>
                <li><a href="/admin/collections">Collections</a></li>
                <li><a href="/admin/shipping">Shipping Rules</a></li>
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false"
                    >Orders <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/orders">Orders</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/admin/orders/ongoing">Open Orders</a></li>
                        <li><a href="/admin/orders/fulfilled">Fulfilled Orders</a></li>
                        <li><a href="/admin/orders/canceled">Canceled Orders</a></li>
                        <li><a href="/admin/orders/archived">Archived Orders</a></li>
                    </ul>
                </li>
                @if($ediblePages->count())
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false"
                    >Site Content <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach($ediblePages as $page)
                            <li><a href="{{ $page['url'] }}">{{ ucwords($page['name']) }}</a></li>
                        @endforeach
                    </ul>
                </li>
                @endif
                <li><a href="/admin/blog/posts">Blog</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="/admin/users">Users</a></li>
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false"
                    >{{ Auth::user()->email }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/users/password/reset">Reset Password</a></li>
                        <li><a href="/admin/logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>