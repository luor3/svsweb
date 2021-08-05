<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="max-h-screen flex flex-col">
        @include('partials.site-navigation')

        <div class="flex flex-col">

            <!-- Remove class [ h-64 ] when adding a card block -->
            <div class="container mx-auto mt-10">

                {{-- @include('partials._vtk.controllers.cutter') --}}
            </div>
        </div>

        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>