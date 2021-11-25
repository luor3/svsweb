<x-guest-layout>
    @push('metas')  
    @include('partials.metas')
    @endpush


    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')

        <div class="flex-grow p-8 text-1xl">          
            @livewire('frontend.jobs.show-form')
        </div>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>
