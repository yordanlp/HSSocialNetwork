<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Harbour Space Social Network</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> --}}
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    @if ( $uses_livewire )
        @livewireStyles
    @endif

</head>

<body id="page-top" style="background-color: #eee">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/">Harbour.Space Network</a>
                        <div class="navbar" style="width: 100%;">
                            @auth
                                <x-Navigation/>
                            <form class="d-flex" style="margin-left: auto" method="get" action="{{route("feed.index")}}">
                                <input class="form-control me-2" type="search" placeholder="Search" value="{{ request()->query('search') ?? "" }}" aria-label="Search" name="search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </form>
                            <livewire:language-selector />
                            @endauth

                            <ul class="navbar-nav mb-2 mb-lg-0 d-flex gap-3" style="margin-left: 10px;">

                                @auth
                                    <li class="nav-item">
                                        <a href="{{route("user.show", auth()->user()->id)}}" style="color: white;" class="d-flex align-items-center gap-2">
                                            <img clas="user-avatar" style="width: 40px; border-radius: 50%;aspect-ratio: 1;" src='{{ auth()->user()->getProfilePictureUrl() }}' alt="user avatar" />
                                            <div>{{auth()->user()->name}}</div>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex align-items-center">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{route('logout')}}" onclick="event.preventDefault();this.closest('form').submit();">
                                                Logout
                                            </a>
                                        </form>
                                    </li>
                                @endauth
                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{route("login")}}">Login</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route("register")}}">Register</a>
                                    </li>
                                @endguest

                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-top: 73px;">
                    {{ $slot }}
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            {{-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer> --}}
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>

@if( $uses_livewire )
    @livewireScripts
@endif

</html>
