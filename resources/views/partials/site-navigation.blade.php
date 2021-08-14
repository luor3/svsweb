<div x-data="{ open: false }" class="bg-indigo-900 px-4 py-4">
    <div class="md:w-1xl lg:mx-auto lg:flex md:items-center md:justify-between">
        <div class="flex justify-between items-center">
            <a href="/" class=" {{ request()->routeIs('homepage') ? 'sm:hidden' : '' }} inline-block py-2 text-white text-3xl font-bold">
                <h1>{{ $app->name }}</h1>
            </a>
            <div class="inline-block cursor-pointer lg:hidden" @click="open = !open">
                <div class="bg-gray-400 w-8 mb-2" style="height: 2px;"></div>
                <div class="bg-gray-400 w-8 mb-2" style="height: 2px;"></div>
                <div class="bg-gray-400 w-8" style="height: 2px;"></div>
            </div>
        </div>


        <div :class="{ 'hidden': !open }" class="hidden lg:block">
            <x-site-link href="{{ url('/demos') }}" :active="request()->routeIs('demos') || request()->getPathInfo() == '/demos'">
                Demos
            </x-site-link>
            <x-site-link href="{{ url('/input-generator') }}" :active="request()->routeIs('input-generator')|| request()->getPathInfo() == '/input-generator'">
                Input Generator
            </x-site-link>
            <x-site-link href="{{ url('/about') }}" :active="request()->getPathInfo() == '/about'">
                About
            </x-site-link>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block lg:inline-block mb-2 lg:mb-0 py-2 px-4 text-gray-700 bg-white hover:bg-gray-300 rounded-lg mr-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block lg:inline-block mb-2 lg:mb-0 py-2 px-4 text-gray-700 bg-white hover:bg-gray-300 rounded-lg mr-2">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block lg:inline-block py-2 px-4 text-white bg-blue-500 hover:bg-gray-900 rounded-lg">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</div>
