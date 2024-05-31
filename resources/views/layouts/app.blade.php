<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="author" content="">
    <meta name="description" content="{{ $metaDescription }}">


    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
    </style>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"
            integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>

    @livewireStyles
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-family-karla">


<!-- Text Header -->
<header class="w-full container mx-auto">
    <div class="flex flex-col items-center py-12">
        <a class="font-bold text-gray-800 uppercase hover:text-gray-700 text-5xl" href="{{route('home')}}">
            Events
        </a>
    </div>
</header>

<!-- Topic Nav -->
<nav class="w-full py-4 border-t border-b bg-gray-100" x-data="{ open: false }">
    <div class="block sm:hidden">
        <a
            href="#"
            class="block md:hidden text-base font-bold uppercase text-center flex justify-center items-center"
            @click="open = !open"
        >
            Topics <i :class="open ? 'fa-chevron-down': 'fa-chevron-up'" class="fas ml-2"></i>
        </a>
    </div>
    <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto">
    <div class="w-full container mx-auto flex flex-col sm:flex-row items-center justify-between text-sm font-bold uppercase mt-0 px-6 py-2">
        <div class="flex flex-col sm:flex-row"> <!-- Use flex column for mobile and flex row for desktop -->
            <a href="{{route('home')}}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Home</a>
            @auth
                @if(Auth::user()->hasVerifiedEmail())
                    @foreach($categories as $category)
                        <a href="{{ route('by-category', $category) }}"
                           class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">{{ $category->category_name }}</a>
                    @endforeach
                @endif
            @else
                @foreach($categories as $category)
                    <a href="{{ route('by-category', $category) }}"
                       class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">{{ $category->category_name }}</a>
                @endforeach
            @endauth
            
        </div>
    </div>
</div>
    <div class="flex justify-end items-center">
                
                @auth
                    @if(Auth::user()->hasVerifiedEmail())
                        <form method="get" action="{{route('search')}}">
                            <input name="q" value="{{request()->get('q')}}"
                                   class="block w-full rounded-md border-0 px-3.5 py-2 t0ext-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 font-medium"
                                   placeholder="Type an hit enter to search anything"/>
                        </form>
                    @endif
                @else
                    <form method="get" action="{{route('search')}}">
                        <input name="q" value="{{request()->get('q')}}"
                               class="block w-full rounded-md border-0 px-3.5 py-2 t0ext-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 font-medium"
                               placeholder="Type an hit enter to search anything"/>
                    </form>
                @endauth
                
                @auth
                
                    <div class="flex sm:items-center sm:ml-6">
                        @if(Auth::user()->hasRole(['Super Admin','Admin', 'Agency']))
                    <a href="https://event.kuchingitsolution.net/admin" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Dashboard</a>
                @endif
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="hover:bg-blue-600 hover:text-white flex items-center rounded py-2 px-4 mx-2">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                
                                @auth
                                    @if(Auth::user()->hasVerifiedEmail())
                                        <x-dropdown-link :href="route('bookmarked-event')">
                                            {{ __('Bookmarked') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>
                                    @endif
                                @else
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                @endauth
                                
                                <!--<a href="{{route('bookmarked-event')}}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Bookmarked</a>-->

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <a href="{{route('login_unimas')}}"
                       class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Login</a>
                    <!--<a href="{{route('register')}}" class="bg-blue-600 text-white rounded py-2 px-4 mx-2">Register</a>-->
                @endauth
            </div>
</nav>


<div class="container mx-auto py-6">

    {{ $slot }}

</div>

<footer class="w-full border-t bg-white pb-12">
    <div class="w-full container mx-auto flex flex-col items-center">
        <div class="uppercase py-6"></div>
    </div>
</footer>

@livewireScripts
</body>
</html>
