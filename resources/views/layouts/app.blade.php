<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Management')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('customers.index') }}">Customer Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customers.index') }}">Home</a>
                </li>
                @if(auth()->check()) 
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customers.create') }}">Create Customer</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ml-auto">

                @if(!auth()->check()) 
                    @if(request()->is('login'))
                        <li class="nav-item">
                            <a class="btn btn-link nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @elseif(request()->is('register'))
                        <li class="nav-item">
                            <a class="btn btn-link nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endif
                @endif

                @if(auth()->check())
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endif 
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        @yield('content')
    </div>
</body>
</html>
