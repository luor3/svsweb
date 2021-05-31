<x-guest-layout>
    @push('metas')
        @include('partials.metas')
    @endpush
    
    <div>
        @include('partials.site-navigation')

        <div class="bg-indigo-900 md:overflow-hidden">
            <div class="px-4 py-10 md:py-0">
                <div class="md:max-w-6xl md:mx-auto">
                    <div class="md:flex md:flex-wrap">
                        <div class="md:w-1/1 text-center md:text-left md:pt-6">
                            <h2 class="font-bold text-white text-2xl md:text-5xl leading-tight mb-4">
                                {{ $app->author }}
                            </h2>

                            <p class="text-indigo-200 md:text-xl md:pr-48">
                                {{ $app->description }}
                            </p>

                            <a href="{{ url('/demos') }}" class="mt-6 mb-12 md:mb-0 md:mt-10 inline-block py-3 px-8 text-white bg-red-500 hover:bg-red-600 rounded-lg shadow">Get Started!</a>
                        </div>
                    </div>
                </div>
            </div>
            <svg class="fill-current text-white hidden md:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill-opacity="1" d="M0,224L1440,32L1440,320L0,320Z"></path>
            </svg>
        </div>

        <p class="text-center p-4 text-gray-600 pt-20 lg:pt-0">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
    </div>
    
</x-guest-layout>