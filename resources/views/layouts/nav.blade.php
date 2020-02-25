<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('articles*') ? 'active' : '' }}" href="{{ route('articles.index') }}">{{ __('Articles') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">{{ __('Catégories') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('keywords*') ? 'active' : '' }}" href="{{ route('keywords.index') }}">{{ __('Mots-clés') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('auteurs*') ? 'active' : '' }}" href="{{ route('auteurs.index') }}">{{ __('Auteurs') }}</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">{{ __('Équipe') }}</a>
                    </li>
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary {{ request()->is('visualisations*') ? 'active' : '' }}" href="{{ route('visualisations.index') }}">{{ __('Visualisations') }}</a>
                </li>
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item ml-2">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown ml-2">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
