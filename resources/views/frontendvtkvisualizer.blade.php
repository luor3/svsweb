<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')
        <div class="flex-grow p-8 text-1xl">          
            <div>
                @if(isset( $vtk_path ))
                    <input id="geometric-file-path" type="text" disabled class="hidden" value="{{ $vtk_path }}"/>
                @endif
                <div id="custom-control" style="margin: auto; width: 70%; display: flex;"></div>
                <div id="custom-vtk-viewer" style="height: 600px;"></div>
            </div>
        </div>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>
